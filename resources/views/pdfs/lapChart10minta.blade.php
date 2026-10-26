<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* ================================================= */
        /* HEADER & KOP SURAT RESMI (Dioptimalkan Landscape) */
        /* ================================================= */
        .header-container {
            width: 100%;
            position: relative;
            text-align: center;
        }
        
        .logo-column {
            position: absolute;
            left: 20px;
            top: 0;
            width: 75px;
        }

        .logo-column img {
            width: 75px;
            height: auto;
        }
        
        .text-column { 
            text-align: center; 
            padding-left: 60px; /* Memberi ruang seimbang karena kertas melebar */
        }
        .text-column h2 { font-size: 15pt; margin: 0; line-height: 1.2; font-weight: bold; letter-spacing: 0.5px; }
        .text-column p.alamat { font-size: 10.5pt; margin: 2px 0 0 0; line-height: 1.3; }

        .header-separator {
            border-top: 2.5px solid #000;
            border-bottom: 0.5px solid #000;
            margin: 8px 0 15px 0;
            height: 1px;
        }

        /* ================================================= */
        /* AREA GRAFIK LANDSCAPE */
        /* ================================================= */
        .chart-box {
            width: 100%;
            text-align: center;
            margin: 15px 0;
            padding: 5px;
            border: 1px solid #b0b0b0;
            background-color: #fff;
        }

        .chart-box img {
            width: 100%;
            /* Batasi tinggi agar grafik tidak memakan ruang ke halaman kedua */
            max-height: 360px; 
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* ================================================= */
        /* TANDA TANGAN */
        /* ================================================= */
        .footer { 
            margin-top: 20px; 
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            vertical-align: top;
        }
        .signature-block { 
            text-align: center; 
            width: 280px;
        }
        .signature-block p { margin: 0; line-height: 1.4; }
    </style>
</head>

<body>

    <div class="header-container">
        <div class="logo-column">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" alt="Logo">
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

    <div class="document-title" style="text-align: center; margin-bottom: 10px;">
        <h3 style="margin: 0; font-weight: bold; font-size: 13pt; text-transform: uppercase; letter-spacing: 0.5px;">{{ $title }}</h3>
        <p style="margin: 2px 0 0 0; font-size: 11pt;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <div class="chart-box">
        <img src="{{ $chartImage }}" alt="Grafik Statistik Permintaan">
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%;">
                <div class="signature-block">
                    <p>Banjarmasin, {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
                    <p>Mengetahui,</p>
                    <br><br><br><br>
                    <p><b><u>Apt. Yohana Rusnayuda, S.Si</u></b></p>
                    <p>NIP. 19790311 200501 2 015</p>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>