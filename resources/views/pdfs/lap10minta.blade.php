<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Permintaan Barang</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            margin: 15px;
        }

        /* ================================================= */
        /* HEADER & KOP SURAT */
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
            width: 65px;
            height: auto;
        }
        
        .text-column { text-align: center; }
        .text-column h2 { font-size: 14pt; margin: 0; line-height: 1.2; font-weight: bold; }
        .text-column p.alamat { font-size: 10pt; margin: 0; line-height: 1.3; }

        .header-separator {
            border-top: 2px solid #000;
            border-bottom: 0.5px solid #000;
            margin: 5px 0 15px 0;
            height: 1px;
        }

        /* ================================================= */
        /* TABEL DATA (5 Kolom Portrait) */
        /* ================================================= */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px 5px;
            word-wrap: break-word;
            vertical-align: middle;
            font-size: 9.5pt; /* Ukuran font dinaikkan agar pas dengan 5 kolom */
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Alokasi Lebar Kolom Proposional (Total 100%) */
        .col-no { width: 6%; }
        .col-nama { width: 34%; }
        .col-kat { width: 25%; }
        .col-satuan { width: 15%; }
        .col-jml { width: 20%; }

        .footer { margin-top: 40px; text-align: right; }
        .signature-block { display: inline-block; width: 250px; text-align: center; }
        .footer p { margin: 0; line-height: 1.5; }
        .text-center { text-align: center; }
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

    <div class="document-title" style="text-align: center; margin-bottom: 15px;">
        <h3 style="margin: 0; font-weight: bold; font-size: 13pt;">REKAPITULASI TOTAL PERMINTAAN BARANG</h3>
        <p style="margin: 0;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Barang</th>
                <th class="col-kat">Kategori</th>
                <th class="col-satuan">Satuan</th>
                <th class="col-jml">Total Diminta</th>
            </tr>
        </thead>
        <tbody>
            {{-- Sesuaikan nama variabel array/collection ($stoks atau $items) jika berbeda di controller Anda --}}
            @forelse ($items ?? $stoks ?? [] as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td style="font-weight: bold;">{{ $item->nama_obat ?? '-' }}</td>
                    <td>{{ $item->nama_kategori ?? '-' }}</td>
                    <td class="text-center">{{ $item->nama_satuan ?? '-' }}</td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ number_format($item->total_diminta, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Data Permintaan Barang Tidak Ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-block">
            <p>Banjarmasin, {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p><b><u>Apt. Yohana Rusnayuda, S.Si</u></b></p>
            <p>NIP. 19790311 200501 2 015</p>
        </div>
    </div>

</body>
</html>