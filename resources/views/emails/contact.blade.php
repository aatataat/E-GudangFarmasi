<x-mail::message>
# Pesan Kontak Baru

Anda telah menerima pesan baru dari formulir kontak.

---

**Nama:** {{ $name }}
**Email:** {{ $email }}
**Subjek:** {{ $subject }}

---

**Isi Pesan:**
{{ $message }}

---

Terima kasih,<br>
Tim UPTD Instalasi Farmasi
</x-mail::message>