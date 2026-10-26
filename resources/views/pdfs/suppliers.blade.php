<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Supplier</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            margin: 25px;
        }

        /* HEADER & KOP SURAT */
        .header-container {
            width: 100%;
            text-align: center; 
            position: relative;
            margin-bottom: 5px;
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
            height: 0;
            padding: 0;
        }

        /* Judul Laporan */
        .document-title {
            text-align: center;
            margin-bottom: 5px;
        }

        .document-title h3 {
            font-size: 14pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .document-title p {
            font-size: 11pt;
            margin: 0;
        }

        /* TABEL DATA */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            word-break: break-word;
            line-height: 1.2;
            vertical-align: top;
            font-size: 10pt;
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
            vertical-align: middle;
        }

        /* Penyesuaian Lebar Kolom Supplier */
        .data-table th:nth-child(1) { width: 7%; }  /* No */
        .data-table th:nth-child(2) { width: 25%; } /* Nama Supplier */
        .data-table th:nth-child(3) { width: 48%; } /* Alamat */
        .data-table th:nth-child(4) { width: 20%; } /* No HP */

        .data-table td:nth-child(1), 
        .data-table td:nth-child(4) { 
            text-align: center; 
        }

        /* FOOTER TANDA TANGAN */
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 10pt;
            width: 100%;
        }
        
        .signature-block {
            width: 40%; 
            margin-left: auto;
            text-align: center;
        }

        .footer p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <div class="header-container">
        <div class="logo-column">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}"
                alt="Logo Dinas Kesehatan">
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

    <div class="document-title">
        <h3>LAPORAN DATA SUPPLIER</h3>
        <p>Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>Nomor HP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $key => $supplier)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $supplier->nama_supplier }}</td>
                    <td>{{ $supplier->alamat }}</td>
                    <td>{{ $supplier->no_hp }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-block">
            <p>Banjarmasin, {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p><b>Apt. Yohana Rusnayuda, S.Si</b></p>
            <p><b>NIP. 19790311 200501 2 015</b></p>
        </div>
    </div>

</body>
</html>