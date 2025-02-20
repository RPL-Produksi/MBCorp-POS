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
            'stok' => $id ? 'nullable|numeric' : 'required|numeric',
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
            $filePath = 'barang' . '/' . $kasir->perusahaan->id;

            $storedFile = $file->storeAs($filePath, $file->hashName());
            $data['foto'] = Storage::url($storedFile);
        }

        $data['perusahaan_id'] = $kasir->perusahaan->id;
        Produk::updateOrCreate(['id' => $id], $data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $user = Auth::user();
        $kasir = Kasir::where('user_id', $user->id)->with('perusahaan')->first();

        if (!$kasir || !$kasir->perusahaan) {
            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "limit" => $length,
                "data" => []
            ]);
        }

        $data = Produk::where('perusahaan_id', $kasir->perusahaan->id)
            ->with('kategori');

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $data->orderBy($columns[$orderBy]['data'], $orderDir);
            } else {
                $data->orderBy('nama', 'asc');
            }
        } else {
            $data->orderBy('nama', 'asc');
        }

        $count = $data->count();
        $countFiltered = $count;

        if (!empty($search['value'])) {
            $data->where(function ($query) use ($search) {
                $query->where('nama', 'LIKE', '%' . $search['value'] . '%')
                    ->orWhere('kode', 'LIKE', '%' . $search['value'] . '%');
            });
            $countFiltered = $data->count();
        }

        $data = $data->skip($start)->take($length)->get();

        return response()->json([
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $countFiltered,
            "limit" => $length,
            "data" => $data
        ]);
    }

    public function dataById($id)
    {
        $barang = Produk::where('id', $id)->with(['kategori'])->first();

        return response()->json($barang);
    }

    public function addStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stok' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $barang = Produk::find($id);
        $barang->stok += $request->stok;
        $barang->save();

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan');
    }

    public function delete(Request $request, $id)
    {
        $barang = Produk::find($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function changeImage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $barang = Produk::find($id);

        $file = $request->file('foto');
        $filePath = 'barang' . '/' . $barang->perusahaan_id;

        $storedFile = $file->storeAs($filePath, $file->hashName());
        $barang->foto = Storage::url($storedFile);
        $barang->save();

        return redirect()->back()->with('success', 'Foto berhasil diubah');
    }
}
