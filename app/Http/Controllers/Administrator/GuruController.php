<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuruController extends Controller
{
    function index(){
        return view('guru.dashboard');
    }
}
