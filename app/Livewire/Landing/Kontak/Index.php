<?php

namespace App\Livewire\Landing\Kontak;

use Livewire\Component;
use App\Mail\ContactMessage; // Impor kelas Mailable
use Illuminate\Support\Facades\Mail; // Impor Mail Facade

class Index extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;

    public function submitForm()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        // LOGIKA PENGIRIMAN EMAIL
        $recipientEmail = 'gokiliat321@gmail.com'; // Ganti dengan email tujuan
        
        Mail::to($recipientEmail)->send(new ContactMessage(
            $this->name,
            $this->email,
            $this->subject,
            $this->message
        ));

        // Tampilkan pesan sukses
        session()->flash('success_message', 'Pesan Anda berhasil dikirim!');

        // Reset formulir
        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.landing.kontak.index');
    }
}