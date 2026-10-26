<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Stok Barang</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            margin: 10px;
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
            width: 60px;
        }

        .logo-column img {
            width: 60px;
            height: auto;
        }
        
        .text-column { text-align: center; }
        .text-column h2 { font-size: 13pt; margin: 0; line-height: 1.2; font-weight: bold; }
        .text-column p.alamat { font-size: 9pt; margin: 0; line-height: 1.3; }

        .header-separator {
            border-top: 2px solid #000;
            border-bottom: 0.5px solid #000;
            margin: 5px 0 10px 0;
            height: 1px;
        }

        /* ================================================= */
        /* TABEL DATA DENGAN ROWSPAN/COLSPAN (11 KOLOM) */
        /* ================================================= */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 3px 2px;
            word-wrap: break-word;
            vertical-align: middle;
            font-size: 7.5pt; /* Diperkecil sedikit agar muat kolom kategori */
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Pengaturan Lebar Kolom Proposional (Total 100%) */
        .col-no { width: 3%; }
        .col-kat { width: 10%; } /* Kolom Baru */
        .col-nama { width: 15%; }
        .col-satuan { width: 6%; }
        .col-batch { width: 8%; }
        .col-ed { width: 9%; }
        .col-jml { width: 6%; }
        .col-harga { width: 10%; }
        .col-total { width: 11%; }
        .col-anggaran { width: 10%; }
        .col-supplier { width: 12%; }

        /* Format Akuntansi Rp */
        .acc-container { display: table; width: 100%; }
        .acc-rp { display: table-cell; text-align: left; width: 12px; }
        .acc-val { display: table-cell; text-align: right; }

        /* ================================================= */
        /* FOOTER */
        /* ================================================= */
        .footer { margin-top: 20px; text-align: right; }
        .signature-block { display: inline-block; width: 220px; text-align: center; }
        .footer p { margin: 0; line-height: 1.4; font-size: 9pt; }
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

    <div style="text-align: center; margin-bottom: 10px;">
        <h3 style="margin: 0; font-size: 12pt; font-weight: bold;">LAPORAN STOK BARANG</h3>
        <p style="margin: 0; font-size: 10pt;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">No</th>
                <th class="col-kat" rowspan="2">Kategori</th> <th class="col-nama" rowspan="2">Nama Barang</th>
                <th class="col-satuan" rowspan="2">Satuan</th>
                <th class="col-batch" rowspan="2">Batch Number</th>
                <th class="col-ed" rowspan="2">Tanggal Kadaluarsa</th>
                <th colspan="3" class="text-center">Persediaan</th>
                <th class="col-anggaran" rowspan="2" class="text-center">Sumber Anggaran</th>
                <th class="col-supplier" rowspan="2">Supplier</th>
            </tr>
            <tr>
                <th class="col-jml">Jumlah Satuan</th>
                <th class="col-harga">Nilai Satuan</th>
                <th class="col-total">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stoks as $key => $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->obat->kategori->nama_kategori ?? 'N/A' }}</td> <td>{{ $item->obat->nama_obat ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->obat->satuan->nama_satuan ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->batch_number ?? 'N/A' }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->translatedFormat('j M y') }}
                    </td>
                    <td class="text-center">
                        {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="acc-container">
                            <span class="acc-rp">Rp</span>
                            <span class="acc-val">{{ number_format($item->harga, 0, ',', '.') }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="acc-container">
                            <span class="acc-rp">Rp</span>
                            <span class="acc-val">{{ number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        {{ $item->sumberAnggaran->nama_anggaran ?? 'N/A' }}
                    </td>
                    <td>{{ $item->supplier->nama_supplier ?? 'N/A' }}</td>
                </tr>
            @endforeach
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