<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitLayanan;

class DashboardController extends Controller
{
    public function index()
    {
        $units = UnitLayanan::all();
        return view('dashboard', compact('units'));
    }
}
