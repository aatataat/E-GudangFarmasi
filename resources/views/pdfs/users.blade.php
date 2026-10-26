<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pengguna Sistem</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 20px;
        }

        /* ================================================= */
        /* KOP SURAT */
        /* ================================================= */
        .header-container {
            width: 100%;
            text-align: center;
            position: relative;
        }

        .logo-column {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
        }

        .logo-column img {
            width: 70px;
            height: auto;
        }

        .text-column {
            text-align: center;
        }

        .text-column h2 {
            font-size: 14pt;
            margin: 0;
            line-height: 1.2;
            font-weight: bold;
        }

        .text-column p.alamat {
            font-size: 10pt;
            margin: 0;
            line-height: 1.3;
        }

        .header-separator {
            border-top: 2px solid #000;
            border-bottom: 0.5px solid #000;
            margin: 10px 0 20px 0;
            height: 1px;
        }

        /* ================================================= */
        /* TABEL DATA USER */
        /* ================================================= */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            word-wrap: break-word;
            vertical-align: middle;
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Lebar Kolom Proposional */
        .col-no {
            width: 8%;
        }

        .col-nama {
            width: 32%;
        }

        .col-email {
            width: 30%;
        }

        .col-faskes {
            width: 30%;
        }

        /* ================================================= */
        /* FOOTER */
        /* ================================================= */
        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .signature-block {
            display: inline-block;
            width: 250px;
            text-align: center;
        }

        .footer p {
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <div class="header-container">
        <div class="logo-column">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}"
                alt="Logo">
        </div>
        <div class="text-column">
            <h2>PEMERINTAH KOTA BANJARMASIN</h2>
            <h2>DINAS KESEHATAN</h2>
            <h2>UPTD INSTALASI FARMASI</h2>
            <p class="alamat">JL. LINGKAR DALAM SELATAN RT 20 KELURAHAN KELAYAN TIMUR</p>
            <p class="alamat"><b>BANJARMASIN</b></p>
        </div>
    </div>

    <div class="header-separator"></div>

    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-weight: bold;">LAPORAN DATA PENGGUNA SISTEM</h3>
        <p style="margin: 0;">Per Tanggal: {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Lengkap</th>
                <th class="col-email">Alamat Email</th>
                <th class="col-faskes">Fasilitas Kesehatan (Faskes)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->faskes->nama_faskes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Data Pengguna Tidak Ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-block">
            <p>Banjarmasin, {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br><br>
            <p><b><u>Apt. Yohana Rusnayuda, S.Si</u></b></p>
            <p>NIP. 19790311 200501 2 015</p>
        </div>
    </div>

</body>

</html>
