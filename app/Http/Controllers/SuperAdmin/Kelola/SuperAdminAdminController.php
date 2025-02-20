<?php

namespace App\Http\Controllers\SuperAdmin\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Perusahaan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminAdminController extends Controller
{
    public function index($perusahaanId)
    {
        $data['perusahaan'] = Perusahaan::where('id', $perusahaanId)->first();

        if (!$data['perusahaan']) {
            return redirect()->route('superadmin.kelola.perusahaan')->with('error', 'Perusahaan tidak ditemukan');
        }

        confirmDelete('Hapus Admin?', 'Apakah anda yakin ingin menghapus admin ini?');
        return view('pages.superadmin.kelola.perusahaan.admin.index', [], ['menu_type' => 'kelola-perusahaan'])->with($data);
    }

    public function store(Request $request, $perusahaanId, $adminId = null)
    {
        $perusahaan = Perusahaan::find($perusahaanId);
        if (!$perusahaan) {
            return redirect()->route('superadmin.kelola.perusahaan')->with('error', 'Perusahaan tidak ditemukan');
        }

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:20|unique:users,nomor_telp,' . ($adminId ? Admin::find($adminId)->user_id : 'NULL'),
            'username' => 'required|string|max:50|unique:users,username,' . ($adminId ? Admin::find($adminId)->user_id : 'NULL'),
            'password' => $adminId ? 'nullable|min:6' : 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userId = $adminId ? Admin::where('id', $adminId)->value('user_id') : null;

        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_telp' => $request->nomor_telp,
            'username' => $request->username,
            'role' => 'admin',
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user = User::updateOrCreate(['id' => $userId], $userData);

        // Simpan atau update data admin
        $admin = Admin::updateOrCreate(['id' => $adminId], [
            'user_id' => $user->id,
            'perusahaan_id' => $perusahaan->id,
        ]);

        // Perbaikan: Gunakan user_id dari admin untuk subscriptions
        Subscription::create([
            'admin_id' => $admin->id, // Harus pakai user->id karena foreign key mengarah ke users.id
            'expired_at' => now()->addDays(30), // Tambahkan 30 hari dari sekarang
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }




    public function data(Request $request, $perusahaanId)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search.value');
        $order = $request->input('order', []);
        $columns = $request->input('columns', []);

        $query = Admin::where('perusahaan_id', $perusahaanId)->with('user');

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
                            User::select('nama_lengkap')->whereColumn('users.id', 'admins.user_id'),
                            $dir
                        );
                    } elseif ($columnName === 'nomor_telp') {
                        $query->orderBy(
                            User::select('nomor_telp')->whereColumn('users.id', 'admins.user_id'),
                            $dir
                        );
                    } elseif ($columnName === 'username') {
                        $query->orderBy(
                            User::select('username')->whereColumn('users.id', 'admins.user_id'),
                            $dir
                        );
                    }
                }
            }
        } else {
            $query->orderBy(
                User::select('nama_lengkap')->whereColumn('users.id', 'admins.user_id'),
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


    public function dataById($perusahaanId, $adminId)
    {
        $admin = Admin::where('perusahaan_id', $perusahaanId)->where('id', $adminId)->with('user')->first();

        return response()->json($admin);
    }

    public function delete($perusahaanId, $adminId)
    {
        $admin = Admin::where('perusahaan_id', $perusahaanId)->where('id', $adminId)->first();

        if (!$admin) {
            return redirect()->route('superadmin.kelola.perusahaan.admin', $perusahaanId)->with('error', 'admin tidak ditemukan');
        }

        $admin->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
