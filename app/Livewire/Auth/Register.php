<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component
{
    public $nama, $email, $password, $password_confirmation;
    public $role = 'Admin';

    public function render()
    {
        return view('livewire.auth.register')->extends('layouts.app')->section('content');
    }

    public function rules()
    {
        return [
            'nama'                  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\']+$/'],
            'email'                 => 'required|email:rfc|unique:users,email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password'              => ['required', 'string', 'min:8', Password::defaults()],
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function updatedNama()
    {
        $this->resetValidation('nama');
    }

    public function updatedEmail()
    {
        $this->resetValidation('email');
    }

    public function updatedPassword()
    {
        $this->resetValidation('password');
        $this->resetValidation('password_confirmation');
    }

    public function updatedPasswordConfirmation()
    {
        $this->resetValidation('password_confirmation');
        $this->resetValidation('password');
    }

    public function registerUser()
    {
        $this->resetValidation();
        $this->validate(
            [
                'nama'                  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\']+$/'],
                'email'                 => 'required|email:rfc|unique:users,email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'password'              => ['required', 'string', 'min:8', Password::defaults()],
                'password_confirmation' => 'required|same:password',
            ],
            [
                'nama.required'                 => 'Nama Tidak Boleh Kosong',
                'nama.regex'                    => 'Nama Tidak Boleh Mengandung Simbol,Nomor Selain Titik dan Apostrof',
                'nama.string'                   => 'Nama Harus Berupa Teks',
                'nama.max'                      => 'Nama Maksimal 255 Karakter',

                'email.required'                => 'Email Tidak Boleh Kosong',
                'email.email'                   => 'Format Email Tidak Valid (Contoh: user@domain.com)',
                'email.regex'                   => 'Format Email Tidak Valid (Contoh: user@domain.com)',
                'email.unique'                  => 'Email Sudah Terdaftar',
                'email.max'                     => 'Email Maksimal 255 Karakter',

                'password.required'             => 'Password Tidak Boleh Kosong',
                'password.min'                  => 'Password Minimal 8 Karakter',
                'password_confirmation.required' => 'Konfirmasi Password Tidak Boleh Kosong',
                'password_confirmation.same'    => 'Konfirmasi Password Tidak Cocok Dengan Password Utama',
            ]
        );

        $user = User::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);
        Auth::login($user, true);
        return redirect()->to('/home');
    }
}
