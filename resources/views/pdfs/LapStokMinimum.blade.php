<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Kritis</title>
    <style>
        @page { margin: 1cm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
        }

        /* ================================================= */
        /* KOP SURAT */
        /* ================================================= */
        .header-container {
            width: 100%;
            display: table;
            margin-bottom: 5px;
        }
        
        .logo-column {
            display: table-cell;
            width: 70px;
            vertical-align: middle;
        }

        .logo-column img {
            width: 70px;
            height: auto;
        }
        
        .text-column {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .text-column h2 { 
            font-size: 14pt; 
            margin: 0; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .text-column p.alamat { 
            font-size: 9pt; 
            margin: 2px 0 0 0; 
            font-style: normal;
        }

        .header-separator {
            border-top: 2.5px solid #000;
            border-bottom: 0.5px solid #000;
            height: 1.5px;
            margin: 10px 0 20px 0;
        }

        /* ================================================= */
        /* ISI DOKUMEN */
        /* ================================================= */
        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .title-section h3 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            font-weight: bold;
        }

        .title-section p {
            margin: 5px 0 0 0;
            font-size: 10pt;
        }

        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            word-wrap: break-word;
            vertical-align: middle;
        }

        .data-table th {
            text-align: center;
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
        }

        /* Status & Badge */
        .text-danger { color: #b71c1c; font-weight: bold; }
        .text-orange { color: #e65100; font-weight: bold; }

        .badge {
            padding: 4px 10px;
            border-radius: 2px;
            font-size: 8.5pt;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
        }
        .bg-danger { border: 1px solid #b71c1c; color: #b71c1c; background-color: #ffebee; }
        .bg-warning { border: 1px solid #e65100; color: #e65100; background-color: #fff3e0; }

        /* Alokasi Kolom */
        .col-no { width: 40px; }
        .col-nama { width: auto; }
        .col-stok { width: 80px; }
        .col-min { width: 80px; }
        .col-status { width: 90px; }

        /* ================================================= */
        /* FOOTER / TANDA TANGAN */
        /* ================================================= */
        .footer-section {
            margin-top: 40px;
            width: 100%;
        }

        .signature-wrapper {
            float: right;
            width: 250px;
            text-align: center;
        }

        .signature-wrapper p { margin: 0; }
        .signature-space { height: 70px; }
    </style>
</head>

<body>

    <div class="header-container">
        <div class="logo-column">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" alt="Logo">
        </div>
        <div class="text-column">
            <h2>Pemerintah Kota Banjarmasin</h2>
            <h2>Dinas Kesehatan</h2>
            <h2>UPTD Instalasi Farmasi</h2>
            <p class="alamat">Jl. Lingkar Dalam Selatan RT 20 Kelurahan Kelayan Timur, Banjarmasin</p>
        </div>
    </div>

    <div class="header-separator"></div>

    <div class="title-section">
        <h3>LAPORAN DATA STOK KRITIS & HABIS</h3>
        <p>Per Tanggal: {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y H:i') }} WITA</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Obat / Barang</th>
                <th class="col-stok">Total Stok</th>
                <th class="col-min">Batas Min.</th>
                <th class="col-status">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stoks as $key => $item)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td> 
                    <td>{{ $item->obat->nama_obat }}</td>
                    <td style="text-align: center;" class="{{ $item->total_stok_gabungan == 0 ? 'text-danger' : 'text-orange' }}">
                        {{ number_format($item->total_stok_gabungan, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        {{ number_format($item->obat->batas_minimum, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        @if ($item->total_stok_gabungan == 0)
                            <span class="badge bg-danger">Habis</span>
                        @else
                            <span class="badge bg-warning">Kritis</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data stok kritis atau habis saat ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-section">
        <div class="signature-wrapper">
            <p>Banjarmasin, {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <div class="signature-space"></div>
            <p><b><u>Apt. Yohana Rusnayuda, S.Si</u></b></p>
            <p>NIP. 19790311 200501 2 015</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>