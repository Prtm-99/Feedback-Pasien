<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitLayanan;
use App\Models\Dokter;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{


public function index()
{
    $units = UnitLayanan::all()->count();
    $dokters = Dokter::all()->count();
    $feedback = Feedback::all()->count();

    return view('admin.dashboard', compact('units', 'dokters', 'feedback'));
}

}
