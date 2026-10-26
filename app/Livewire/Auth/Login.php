<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Login extends Component
{
    public $email, $password, $remember;

    public function render()
    {
        return view('livewire.auth.login')->extends('layouts.app')->section('content');
    }

    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function loginUser()
    {
        // 1. Validasi formulir menggunakan aturan dari metode rules()
        $this->validate();

        $throttlekey = strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttlekey, 5)) {
            $this->addError('email', __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($throttlekey)
            ]));
            return null;
        }

        // 2. Coba otentikasi
        if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($throttlekey);
            $this->addError('email', 'Email atau Password Salah!');
            return null;
        }

        // 3. Reset rate limiter setelah berhasil login
        RateLimiter::clear($throttlekey);

        // 4. Redirect berdasarkan peran pengguna
        if (Auth::user()->role === 'Admin') {
            return redirect()->route('dashboard');
        } 
        elseif (Auth::user()->role === 'Pegawai') {
            return redirect()->route('dashboard');
        } 
        elseif (Auth::user()->role === 'Pimpinan') {
            return redirect()->route('dashboard');
        }
        elseif (Auth::user()->role === 'Faskes') {
            return redirect()->route('dashboard');
        }

        // 5. Redirect default jika peran tidak dikenali
        return redirect()->route('homepage');
    }
}
