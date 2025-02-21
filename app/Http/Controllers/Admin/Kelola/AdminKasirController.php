<?php

namespace App\Http\Controllers\Admin\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Kasir;
use App\Models\Owner;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminKasirController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $admin = Admin::where('user_id', $user->id)->first();
        $data['perusahaan'] = Perusahaan::where('id', $admin->perusahaan->id)->with('owner')->first();

        confirmDelete('Hapus Kasir?', 'Apakah anda yakin ingin menghapus kasir ini?');
        return view('pages.admin.kelola.kasir.index', [], ['menu_type' => 'kelola-kasir'])->with($data);
    }

    public function store(Request $request, $kasirId = null)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:20|unique:users,nomor_telp,' . ($kasirId ? Kasir::find($kasirId)->user_id : 'NULL'),
            'username' => 'required|string|max:50|unique:users,username,' . ($kasirId ? Kasir::find($kasirId)->user_id : 'NULL'),
            'password' => $kasirId ? 'nullable|min:6' : 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userId = $kasirId ? Kasir::where('id', $kasirId)->value('user_id') : null;

        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_telp' => $request->nomor_telp,
            'username' => $request->username,
            'role' => 'kasir',
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $admin = Admin::where('user_id', Auth::user()->id)->first();
        $perusahaan = Perusahaan::where('id', $admin->perusahaan->id)->first();

        $user = User::updateOrCreate(['id' => $userId], $userData);

        Kasir::updateOrCreate(['id' => $kasirId], [
            'user_id' => $user->id,
            'perusahaan_id' => $perusahaan->id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search.value');
        $order = $request->input('order', []);
        $columns = $request->input('columns', []);

        $admin = Admin::where('user_id', Auth::user()->id)->first();
        $perusahaan = Perusahaan::where('id', $admin->perusahaan->id)->first();

        $query = Kasir::where('perusahaan_id', $perusahaan->id)->with('user');

        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', '%' . $search . '%')
                    ->orWhere('nomor_telp', 'LIKE', '%' . $search . '%')
                    ->orWhere('username', 'LIKE', '%' . $search . '%');
            });
        }

        if (!empty($order)) {
            foreach ($order as $ord) {
                $columnIndex = $ord['column'];
                $dir = $ord['dir'];

                if (isset($columns[$columnIndex])) {
                    $columnName = $columns[$columnIndex]['data'];

                    if ($columnName === 'nama_lengkap') {
                        $query->orderBy(
                            User::select('nama_lengkap')->whereColumn('users.id', 'kasirs.user_id'),
                            $dir
                        );
                    } elseif ($columnName === 'nomor_telp') {
                        $query->orderBy(
                            User::select('nomor_telp')->whereColumn('users.id', 'kasirs.user_id'),
                            $dir
                        );
                    } elseif ($columnName === 'username') {
                        $query->orderBy(
                            User::select('username')->whereColumn('users.id', 'kasirs.user_id'),
                            $dir
                        );
                    }
                }
            }
        } else {
            $query->orderBy(
                User::select('nama_lengkap')->whereColumn('users.id', 'kasirs.user_id'),
                'asc'
            );
        }

        $count = $query->count();
        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $data
        ]);
    }


    public function dataById($kasirId)
    {
        $admin = Admin::where('user_id', Auth::user()->id)->first();
        $perusahaan = Perusahaan::where('id', $admin->perusahaan->id)->first();

        $kasir = Kasir::where('perusahaan_id', $perusahaan->id)->where('id', $kasirId)->with('user')->first();

        return response()->json($kasir);
    }

    public function delete($id)
    {
        $kasir = Kasir::find($id);
        $user = User::find($kasir->user_id);

        if (!$kasir) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
