<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
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
        .data-table td:first-child { /* Kolom No */
            text-align: center;
            width: 5%; /* Lebar relatif kecil untuk 'No' */
        }
        .data-table td:nth-child(2) { /* Kolom Nama */
            width: 25%; /* Beri lebar yang cukup untuk Nama */
        }
        .data-table td:nth-child(3) { /* Kolom Alamat (dengan potensi teks sangat panjang) */
            width: 50%; /* Beri lebar yang LEBIH BESAR untuk Alamat */
        }
        .data-table td:nth-child(4) { /* Kolom No. HP */
            width: 20%; /* Beri lebar untuk No. HP */
        }
        /* Pastikan total lebar kolom (5+25+50+20) = 100% */

        .footer {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KABUPATEN [NAMA KABUPATEN]</h2>
        <p>DINAS [NAMA DINAS]</p>
        <p>Jalan [Nama Jalan] Nomor [Nomor], [Nama Kota]</p>
        <hr>
    </div>

    <div class="document-info">
        <div class="align-right">
            <p>[Nama Kota], {{ now()->timezone('Asia/Makassar')->format('d F Y') }}</p>
        </div>
        <table>
            <tbody>
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>[Nomor Surat]</td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td>Laporan Data Pengguna</td>
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
        Bersama surat ini, kami lampirkan data supplier yang terdaftar pada sistem kami per tanggal
        {{ now()->timezone('Asia/Makassar')->format('d F Y') }}.
        Data ini disajikan dalam format tabel sebagai berikut:
    </p>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jenis Obat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jenis as $key => $jenis)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $jenis->nama_jenis }}</td>
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
        <p>([Nama Pejabat])</p>
    </div>

</body>
</html>