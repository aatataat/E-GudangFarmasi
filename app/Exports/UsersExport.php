<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function map($user): array
    {
        return [
            $user->nama,
            $user->email,
            $user->faskes->nama_faskes ?? '-', // Nama faskes dari relasi
            $user->role,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Fasilitas Kesehatan',
            'Role',
        ];
    }
}