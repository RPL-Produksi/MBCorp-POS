<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        return view('pages.superadmin.dashboard.index', [], ['menu_type' => 'dashboard']);
    }
}
