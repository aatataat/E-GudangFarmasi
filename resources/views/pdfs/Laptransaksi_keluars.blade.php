<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Transaksi Keluar</title>
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
        /* TABEL DATA (Optimasi 14 Kolom) */
        /* ================================================= */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            word-wrap: break-word;
            vertical-align: middle;
            font-size: 7.5pt;
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Alokasi Lebar Kolom Proposional (Total 100%) */
        .col-no { width: 3%; }
        .col-tgl { width: 7%; }
        .col-no-surat { width: 10%; } /* Lebar untuk nomor surat */
        .col-tujuan { width: 9%; }
        .col-nama { width: 11%; }
        .col-kat { width: 8%; }
        .col-batch { width: 7%; }
        .col-ed { width: 5%; }
        .col-satuan { width: 5%; }
        .col-jml { width: 4%; }
        .col-harga { width: 8%; }
        .col-total { width: 9%; }
        .col-anggaran { width: 9%; }
        .col-penyedia { width: 10%; }

        /* Solusi Mencegah Teks Overlap di DomPDF */
        .acc-table { width: 100%; border: none !important; margin: 0; padding: 0; }
        .acc-table td { border: none !important; padding: 0 1px !important; font-size: 7.5pt; }
        .acc-rp { text-align: left; width: 12px; }
        .acc-val { text-align: right; }

        .footer { margin-top: 30px; text-align: right; }
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

    <div class="document-title" style="text-align: center; margin-bottom: 10px;">
        <h3 style="margin: 0; font-weight: bold; font-size: 14pt;">LAPORAN BARANG KELUAR</h3>
        <p style="margin: 0;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-tgl">Tgl Keluar</th>
                <th class="col-no-surat">No. Surat</th>
                <th class="col-tujuan">Tujuan</th>
                <th class="col-nama">Nama Barang</th>
                <th class="col-kat">Kategori</th>
                <th class="col-batch">No Batch</th>
                <th class="col-ed">Tgl ED</th>
                <th class="col-satuan">Satuan</th>
                <th class="col-jml">Jml</th>
                <th class="col-harga">Harga Satuan</th>
                <th class="col-total">Total</th>
                <th class="col-anggaran">Sumber Anggaran</th>
                <th class="col-penyedia">Penyedia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi_keluar as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_keluar)->translatedFormat('d/m/y') }}
                    </td>
                    <td>{{ $item->permintaan->nomor_surat ?? '-' }}</td>
                    <td>{{ $item->permintaan->faskes->nama_faskes ?? '-' }}</td>
                    <td>{{ $item->stok->obat->nama_obat ?? '-' }}</td>
                    <td>{{ $item->stok->obat->kategori->nama_kategori ?? '-' }}</td>
                    <td class="text-center">{{ $item->batch_number }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('M-y') }}
                    </td>
                    <td class="text-center">{{ $item->stok->obat->satuan->nama_satuan ?? '-' }}</td>
                    <td class="text-center">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>
                        <table class="acc-table">
                            <tr>
                                <td class="acc-rp">Rp</td>
                                <td class="acc-val">{{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="acc-table">
                            <tr>
                                <td class="acc-rp">Rp</td>
                                <td class="acc-val">{{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>{{ $item->stok->sumberAnggaran->nama_anggaran ?? '-' }}</td>
                    <td>{{ $item->stok->supplier->nama_supplier ?? '-' }}</td>
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