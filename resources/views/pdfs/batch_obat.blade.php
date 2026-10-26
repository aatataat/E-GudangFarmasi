<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Batch Obat</title> {{-- Judul dokumen diubah --}}
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 25px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2, .header p {
            margin: 0;
            line-height: 1.2;
        }
        .header hr {
            border: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .document-info {
            margin-top: 20px;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        .document-info p {
            margin: 0;
        }
        .document-info .align-right {
            text-align: right;
            padding-right: 0;
        }
        .document-info table {
            width: auto;
            border-collapse: collapse;
            margin-top: 10px;
            border: none;
            margin-left: auto;
            margin-right: 0;
        }
        .document-info table td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }
        .document-info table td:first-child {
            width: 80px;
        }
        .document-info table td:nth-child(2) {
            width: 10px;
        }

        /* --- Perbaikan utama ada di sini untuk tabel data --- */
        .data-table {
            width: 100%; /* Pastikan tabel mengambil 100% lebar yang tersedia */
            table-layout: fixed; /* Ini SANGAT PENTING: memaksa lebar kolom dihormati */
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px;
            /* Properti word-break ditambahkan sebagai fallback/pelengkap */
            word-break: break-all; /* Memecah kata di mana saja jika perlu */
            overflow-wrap: break-word; /* Modern equivalent */
        }
        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .data-table td {
            text-align: left;
        }
        /* Penyesuaian lebar kolom untuk BatchObat */
        .data-table td:first-child { /* Kolom No */
            text-align: center;
            width: 5%;
        }
        .data-table td:nth-child(2) { /* Kolom Batch Number */
            width: 15%;
        }
        .data-table td:nth-child(3) { /* Kolom Tanggal Masuk */
            width: 15%;
        }
        .data-table td:nth-child(4) { /* Kolom Tanggal Kadaluarsa */
            width: 15%;
        }
        .data-table td:nth-child(5) { /* Kolom Jumlah */
            text-align: center;
            width: 10%;
        }
        .data-table td:nth-child(6) { /* Kolom Nama Obat */
            width: 20%;
        }
        .data-table td:nth-child(7) { /* Kolom Rak Penyimpanan */
            width: 20%;
        }
        /* Pastikan total lebar kolom (5+15+15+15+10+20+20) = 100% */

        .footer {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KABUPATEN [NAMA KABUPATEN]</h2> {{-- Placeholder --}}
        <p>DINAS [NAMA DINAS]</p> {{-- Placeholder --}}
        <p>Jalan [Nama Jalan] Nomor [Nomor], [Nama Kota]</p> {{-- Placeholder --}}
        <hr>
    </div>

    <div class="document-info">
        <div class="align-right">
            <p>[Nama Kota], {{ now()->timezone('Asia/Makassar')->format('d F Y') }}</p> {{-- Placeholder --}}
        </div>
        <table>
            <tbody>
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>[Nomor Permintaan]</td> {{-- Placeholder --}}
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td>Laporan Data Batch Obat</td> {{-- Perihal diubah --}}
                </tr>
            </tbody>
        </table>
    </div>

    <p style="margin-top: 40px;">Kepada Yth.,<br>
    Bapak/Ibu Pimpinan<br>
    Di tempat</p>

    <p style="text-align: justify; text-indent: 30px;">
        Dengan hormat,
        <br><br>
        Bersama surat ini, kami lampirkan data batch obat yang terdaftar pada sistem kami per tanggal
        {{ now()->timezone('Asia/Makassar')->format('d F Y') }}.
        Data ini disajikan dalam format tabel sebagai berikut:
    </p>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th> {{-- Header kolom baru --}}
                <th>Batch Number</th> {{-- Header kolom diubah --}}
                <th>Tanggal Masuk</th> {{-- Header kolom diubah --}}
                <th>Tanggal Kedaluwarsa</th> {{-- Header kolom diubah --}}
                <th>Jumlah</th> {{-- Header kolom baru --}}
                <th>Rak Penyimpanan</th> {{-- Header kolom baru --}}
            </tr>
        </thead>
        <tbody>
            {{-- Variabel data yang dilewatkan dari controller/Livewire harus bernama 'batchObat' --}}
            @foreach ($batchObat as $key => $item) 
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->obat->nama_obat ?? 'N/A' }}</td> {{-- Mengakses nama obat melalui relasi --}}
                <td>{{ $item->batch_number }}</td>
                {{-- Format tanggal agar lebih mudah dibaca --}}
                <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d F Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d F Y') }}</td>
                <td style="text-align: center;">{{ $item->jumlah }}</td>
                <td>{{ $item->rak_penyimpanan->kode_rak ?? 'N/A' }}</td> {{-- Mengakses kode rak melalui relasi --}}
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 40px; text-align: justify; text-indent: 30px;">
        Demikian laporan ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.
    </p>

    <div class="footer">
        <p>Hormat kami,</p>
        <br><br><br>
        <p>([Nama Pejabat])</p> {{-- Placeholder --}}
    </div>

</body>
</html>
