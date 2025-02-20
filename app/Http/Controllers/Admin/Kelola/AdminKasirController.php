<?php

namespace App\Http\Controllers\Admin\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Kasir;
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

        if (!$admin) {
            return abort(403, 'Anda tidak memiliki akses');
        }

        $kasir = Kasir::where('perusahaan_id', $admin->perusahaan_id)->with('user')->get();
        return view('pages.admin.kelola.kasir.index', compact('user', 'kasir'));
    }

    public function storeShow()
    {
        return view('pages.admin.kelola.kasir.store');
    }

    public function editShow($id)
    {
        $kasir = Kasir::find($id);
        $user = User::find($kasir->user_id);
        return view('pages.admin.kelola.kasir.edit', compact('user'));
    }

    public function passwordShow($id)
    {
        $kasir = Kasir::find($id);
        $user = User::find($kasir->user_id);
        return view('pages.admin.kelola.kasir.password', compact('user'));
    }

    public function store(Request $request, $kasirId = null)
    {
        $user = Auth::user();
        $admin = Admin::where('user_id', $user->id)->first();

        if (!$admin) {
            return abort(403, 'Anda tidak memiliki akses');
        }

        $perusahaanId = $admin->perusahaan_id;

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:20|unique:users,nomor_telp,' . ($kasirId ? Kasir::find($kasirId)->user_id : 'NULL'),
            'username' => 'required|string|max:50|unique:users,username,' . ($kasirId ? Kasir::find($kasirId)->user_id : 'NULL'),
            'password' => $kasirId ? 'nullable|min:6' : 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

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

        $user = User::updateOrCreate(['id' => $userId], $userData);

        Kasir::updateOrCreate(['id' => $kasirId], [
            'user_id' => $user->id,
            'perusahaan_id' => $perusahaanId,
        ]);

        return redirect()->route('admin.kelola.kasir')->with('success', 'Data berhasil disimpan');
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

        return redirect()->route('admin.kelola.kasir')->with('success', 'Owner berhasil diperbarui');
    }

    public function delete($id)
    {
        $kasir = Kasir::find($id);

        if (!$kasir) {
            return redirect()->back()->with('error', 'Kasir tidak ditemukan');
        }

        $user = User::find($kasir->user_id);
        $kasir->delete();
        $userOwnerCount = Kasir::where('user_id', $user->id)->count();
        if ($userOwnerCount == 0) {
            $user->delete();
        }

        return redirect()->back()->with('success', 'Kasir berhasil dihapus');
    }
}
