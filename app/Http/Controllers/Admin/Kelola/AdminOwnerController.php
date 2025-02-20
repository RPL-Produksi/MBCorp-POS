<?php

namespace App\Http\Controllers\Admin\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminOwnerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $admin = Admin::where('user_id', $user->id)->first();

        if (!$admin) {
            return abort(403, 'Anda tidak memiliki akses');
        }

        $owner = Owner::where('perusahaan_id', $admin->perusahaan_id)->with('user')->get();
        return view('pages.admin.kelola.owner.index', compact('user', 'owner'));
    }

    public function storeShow()
    {
        return view('pages.admin.kelola.owner.store');
    }

    public function editShow($id)
    {
        $owner = Owner::find($id);
        $user = User::find($owner->user_id);
        return view('pages.admin.kelola.owner.edit', compact('user'));
    }

    public function passwordShow($id)
    {
        $owner = Owner::find($id);
        $user = User::find($owner->user_id);
        return view('pages.admin.kelola.owner.password', compact('user'));
    }

    public function store(Request $request, $ownerId = null)
    {
        $user = Auth::user();
        $admin = Admin::where('user_id', $user->id)->first();

        if (!$admin) {
            return abort(403, 'Anda tidak memiliki akses');
        }

        $perusahaanId = $admin->perusahaan_id;

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
            'perusahaan_id' => $perusahaanId,
        ]);

        return redirect()->route('admin.kelola.owner')->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:20|unique:users,nomor_telp,' . $user->id,
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6,' . $user->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_telp' => $request->nomor_telp,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.kelola.owner')->with('success', 'Owner berhasil diperbarui');
    }

    public function delete($id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            return redirect()->back()->with('error', 'Owner tidak ditemukan');
        }

        $user = User::find($owner->user_id);
        $owner->delete();
        $userOwnerCount = Owner::where('user_id', $user->id)->count();
        if ($userOwnerCount == 0) {
            $user->delete();
        }

        return redirect()->back()->with('success', 'Owner berhasil dihapus');
    }
}
