<?php

namespace App\Http\Controllers\SuperAdmin\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminPerusahaanController extends Controller
{
    public function index()
    {
        confirmDelete('Hapus Perusahaan?', 'Apakah Anda yakin ingin menghapus perusahaan ini?');
        return view('pages.superadmin.kelola.perusahaan.index', [], ['menu_type' => 'kelola-perusahaan']);
    }

    public function form($id = null)
    {
        $data['perusahaan'] = Perusahaan::where('id', $id)->first();

        return view('pages.superadmin.kelola.perusahaan.form', [], ['menu_type' => 'kelola-perusahaan'])->with($data);
    }

    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'nomor_telp' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        Perusahaan::updateOrCreate(['id' => $id], $request->all());
        return redirect()->route('superadmin.kelola.perusahaan')->with('success', 'Data berhasil disimpan');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = Perusahaan::query();

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

    public function delete($id)
    {
        $perusahaan = Perusahaan::where('id', $id)->first();
        if ($perusahaan) {
            $perusahaan->delete();
            return redirect()->route('superadmin.kelola.perusahaan')->with('success', 'Data berhasil dihapus');
        }

        return redirect()->route('superadmin.kelola.perusahaan')->with('error', 'Data tidak ditemukan');
    }
}
