<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $data['user'] = Auth::user();
        $data['kasir'] = Kasir::where('user_id', $data['user']->id)->first();

        return view('pages.kasir.dashboard.index', [], ['menu_type' => 'dashboard'])->with($data);
    }
}
