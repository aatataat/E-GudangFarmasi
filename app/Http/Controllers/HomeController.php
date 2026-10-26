<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Cek peran pengguna dan arahkan ke dashboard yang sesuai
        if (Auth::user()->role === 'Admin') {
            return redirect()->route('superadmin.user.index');
        } elseif (Auth::user()->role === 'Pegawai') {
            return redirect()->route('admin.barang.index');
        }
        elseif (Auth::user()->role === 'Faskes') {
            return redirect()->route('admin.barang.index');
        } 
        elseif (Auth::user()->role === 'Pimpinan') {
            return redirect()->route('admin.barang.index');
        }

        // Fallback jika peran tidak ditemukan
        return redirect('/');
    }
}
