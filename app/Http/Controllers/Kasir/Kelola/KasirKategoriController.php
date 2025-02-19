<?php

namespace App\Http\Controllers\Kasir\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KasirKategoriController extends Controller
{
    public function index()
    {
        $data['user'] = Auth::user();
        $data['kasir'] = Kasir::where('user_id', $data['user']->id)->first();

        confirmDelete('Hapus Kategori?', 'Apakah anda yakin ingin menghapus kategori ini?');
        return view('pages.kasir.kelola.kategori.index', [], ['menu_type' => 'kelola', 'sub_menu_type' => 'kategori'])->with($data);
    }

    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user = Auth::user();
        $kasir = Kasir::where('user_id', $user->id)->with('perusahaan')->first();

        Kategori::updateOrCreate(['id' => $id], [
            'nama' => $request->nama,
            'perusahaan_id' => $kasir->perusahaan->id
        ]);

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

        $data = Kategori::query()->where('perusahaan_id', $kasir->perusahaan->id);

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
            $data->where('nama', 'LIKE', '%' . $search['value'] . '%');
            $countFiltered = $data->count();
        }

        $data = $data->skip($start)->take($length)->get();

        $response = [
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $countFiltered,
            "limit" => $length,
            "data" => $data
        ];

        return response()->json($response);
    }

    public function dataById($id)
    {
        $kategori = Kategori::where('id', $id)->first();

        return response()->json($kategori);
    }

    public function delete($id)
    {
        $kategori = Kategori::where('id', $id)->first();

        $kategori->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
