<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function create() 
    {
        return view('auth.login'); 
    }

    public function store(Request $request)
    {
        // Nanti kita isi dengan logika autentikasi
    }

    public function destroy(Request $request)
    {
        // Nanti kita isi dengan logika logout
    }
}