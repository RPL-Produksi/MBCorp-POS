<?php

namespace App\Http\Controllers\SuperAdmin\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SuperAdminOwnerController extends Controller
{
    public function index($perusahaanId)
    {
        $data['perusahaan'] = Perusahaan::where('id', $perusahaanId)->first();

        if (!$data['perusahaan']) {
            return redirect()->route('superadmin.kelola.perusahaan')->with('error', 'Perusahaan tidak ditemukan');
        }

        confirmDelete('Hapus Owner?', 'Apakah anda yakin ingin menghapus owner ini?');
        return view('pages.superadmin.kelola.perusahaan.owner.index', [], ['menu_type' => 'kelola-perusahaan'])->with($data);
    }

    public function store(Request $request, $perusahaanId, $ownerId = null)
    {
        $perusahaan = Perusahaan::find($perusahaanId);
        if (!$perusahaan) {
            return redirect()->route('superadmin.kelola.perusahaan')->with('error', 'Perusahaan tidak ditemukan');
        }

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:20|unique:users,nomor_telp,' . ($ownerId ? Owner::find($ownerId)->user_id : 'NULL'),
            'username' => 'required|string|max:50|unique:users,username,' . ($ownerId ? Owner::find($ownerId)->user_id : 'NULL'),
            'password' => $ownerId ? 'nullable|min:6' : 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userId = $ownerId ? Owner::where('id', $ownerId)->value('user_id') : null;

        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_telp' => $request->nomor_telp,
            'username' => $request->username,
            'role' => 'owner',
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user = User::updateOrCreate(['id' => $userId], $userData);

        Owner::updateOrCreate(['id' => $ownerId], [
            'user_id' => $user->id,
            'perusahaan_id' => $perusahaan->id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function data(Request $request, $perusahaanId)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $order = $request->input('order');

        $query = Owner::where('perusahaan_id', $perusahaanId)->with('user');

        if (!empty($search['value'])) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', '%' . $search['value'] . '%');
            });
        }

        $query->whereHas('user')->orderBy(
            User::select('nama_lengkap')->whereColumn('users.id', 'owners.user_id'),
            'asc'
        );

        $count = $query->count();
        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "limit" => $length,
            "data" => $data
        ]);
    }

    public function dataById($perusahaanId, $ownerId)
    {
        $owner = Owner::where('perusahaan_id', $perusahaanId)->where('id', $ownerId)->with('user')->first();

        return response()->json($owner);
    }

    public function delete($perusahaanId, $ownerId)
    {
        $owner = Owner::where('perusahaan_id', $perusahaanId)->where('id', $ownerId)->first();

        if (!$owner) {
            return redirect()->route('superadmin.kelola.perusahaan.owner', $perusahaanId)->with('error', 'Owner tidak ditemukan');
        }

        $owner->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
