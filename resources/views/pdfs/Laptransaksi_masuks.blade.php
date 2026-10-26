<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Transaksi Masuk</title>
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
            width: 70px;
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
        /* TABEL DATA (13 Kolom - Dioptimalkan) */
        /* ================================================= */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px 3px;
            word-wrap: break-word;
            vertical-align: middle;
            font-size: 7.5pt; /* Sedikit diperkecil agar muat 13 kolom */
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Penyesuaian Lebar Kolom Proposional (Total 100%) */
        .col-no { width: 3%; }
        .col-trans { width: 8%; }
        .col-tgl-m { width: 7%; }
        .col-nama { width: 12%; }
        .col-kat { width: 9%; } /* Kolom Baru */
        .col-satuan { width: 5%; }
        .col-batch { width: 7%; }
        .col-ed { width: 6%; }
        .col-jml { width: 4%; }
        .col-harga { width: 9%; }
        .col-total { width: 10%; }
        .col-anggaran { width: 10%; }
        .col-supplier { width: 10%; }

        /* Format Akuntansi */
        .acc-container { display: table; width: 100%; }
        .acc-rp { display: table-cell; text-align: left; width: 15px; }
        .acc-val { display: table-cell; text-align: right; }

        .footer { margin-top: 30px; text-align: right; }
        .signature-block { display: inline-block; width: 250px; text-align: center; }
        .footer p { margin: 0; line-height: 1.5; }
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
        <h3 style="margin: 0; font-weight: bold; font-size: 14pt;">LAPORAN BARANG MASUK</h3>
        <p style="margin: 0;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-trans">No. Transaksi</th>
                <th class="col-tgl-m">Tgl Masuk</th>
                <th class="col-nama">Nama Barang</th>
                <th class="col-kat">Kategori</th>
                <th class="col-satuan">Satuan</th>
                <th class="col-batch">No Batch</th>
                <th class="col-ed">Tgl ED</th>
                <th class="col-jml">Jml</th>
                <th class="col-harga">Harga Satuan</th>
                <th class="col-total">Total</th>
                <th class="col-anggaran">Anggaran</th>
                <th class="col-supplier">Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi_masuk as $key => $item)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td>{{ $item->no }}</td>
                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d/m/y') }}
                    </td>
                    <td>{{ $item->obat->nama_obat ?? '-' }}</td>
                    <td>{{ $item->obat->kategori->nama_kategori ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $item->obat->satuan->nama_satuan ?? 'N/A' }}</td>
                    <td>{{ $item->batch_number }}</td>
                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('M-y') }}
                    </td>
                    <td style="text-align: center;">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
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
                    <td>{{ $item->sumberAnggaran->nama_anggaran ?? '-' }}</td>
                    <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
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