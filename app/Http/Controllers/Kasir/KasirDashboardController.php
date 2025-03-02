<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $data['user'] = Auth::user();
        $data['kasir'] = Kasir::where('user_id', $data['user']->id)->first();

        return view('pages.kasir.dashboard.index', [], ['menu_type' => 'dashboard'])->with($data);
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

    public function keranjangData()
    {
        $user = Auth::user();
        $kasir = Kasir::where('user_id', $user->id)->first();

        $keranjang = Keranjang::where('kasir_id', $kasir->id)
            ->with('produk')
            ->get();

        return response()->json($keranjang);
    }

    public function tambahKeranjang(Request $request, $id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        $user = Auth::user();
        $kasir = Kasir::where('user_id', $user->id)->first();

        $keranjang = new Keranjang();
        $keranjang->produk_id = $produk->id;
        $keranjang->kasir_id = $kasir->id;
        $keranjang->perusahaan_id = $produk->perusahaan_id;
        $keranjang->save();

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang'); 
    }
}
