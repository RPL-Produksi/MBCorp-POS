<?php

namespace App\Http\Controllers\Kasir\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KasirBarangController extends Controller
{
    public function index()
    {
        $data['user'] = Auth::user();
        $data['kasir'] = Kasir::where('user_id', $data['user']->id)->first();

        $data['barang'] = Produk::where('perusahaan_id', $data['kasir']->perusahaan_id)->get();
        $data['kategori'] = Kategori::where('perusahaan_id', $data['kasir']->perusahaan_id)->get();

        confirmDelete('Hapus barang?', 'Apakah anda yakin ingin menghapus barang ini?');
        return view('pages.kasir.kelola.barang.index', [], ['menu_type' => 'kelola', 'sub_menu_type' => 'barang'])->with($data);
    }

    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'kode' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user = Auth::user();
        $kasir = Kasir::where('user_id', $user->id)->with('perusahaan')->first();

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filePath = 'barang' . '/' . $kasir->perusahaan->id . '/';

            $storedFile = $file->storeAs($filePath, $file->hashName());
            $data['foto'] = Storage::url($storedFile);
        }

        $data['perusahaan_id'] = $kasir->perusahaan->id;
        Produk::updateOrCreate(['id' => $id], $data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
