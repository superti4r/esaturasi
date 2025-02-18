<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardGuru extends Controller
{
    function index(){
        return view('guru.dashboard');
    }
}
