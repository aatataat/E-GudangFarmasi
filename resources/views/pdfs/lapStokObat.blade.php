<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kartu Stok Barang</title>
    <style>
        @page { size: A4 landscape; margin: 8mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; margin: 5px; }

        /* KOP SURAT */
        .header-container { width: 100%; text-align: center; position: relative; }
        .logo-column { position: absolute; left: 0; top: 0; width: 60px; }
        .logo-column img { width: 60px; height: auto; }
        .text-column { text-align: center; }
        .text-column h2 { font-size: 13pt; margin: 0; line-height: 1.2; font-weight: bold; }
        .text-column p.alamat { font-size: 9pt; margin: 0; line-height: 1.3; }
        .header-separator { border-top: 2px solid #000; border-bottom: 0.5px solid #000; margin: 5px 0 10px 0; height: 1px; }

        /* TABEL DATA */
        .data-table { width: 100%; table-layout: fixed; border-collapse: collapse; }
        .data-table th, .data-table td { 
            border: 1px solid #000; 
            padding: 2px 1px; 
            word-wrap: break-word; 
            vertical-align: middle; 
            font-size: 6.5pt; /* Diperkecil sedikit agar muat tambahan kolom supplier */
        }
        .data-table th { text-align: center; background-color: #f2f2f2; font-weight: bold; }

        /* Format Akuntansi Rp (Mencegah Overlap) */
        .acc-table { width: 100%; border: none !important; margin: 0; padding: 0; }
        .acc-table td { border: none !important; padding: 0 1px !important; font-size: 6.5pt; }
        .acc-rp { text-align: left; width: 10px; }
        .acc-val { text-align: right; }

        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: right; }
        .signature-block { display: inline-block; width: 220px; text-align: center; }
        .footer p { margin: 0; line-height: 1.4; font-size: 9pt; }
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
        <h3 style="margin: 0; font-size: 11pt; font-weight: bold;">LAPORAN DINAMIKA BARANG</h3>
        <p style="margin: 0; font-size: 9pt;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 2%;">No</th>
                <th rowspan="2" style="width: 7%;">Kategori</th>
                <th rowspan="2" style="width: 9%;">Nama Barang</th>
                <th rowspan="2" style="width: 3%;">Sat</th>
                <th rowspan="2" style="width: 5%;">Batch</th>
                <th rowspan="2" style="width: 5%;">ED</th>
                <th colspan="3">Persediaan (Awal)</th>
                <th colspan="3">Penerimaan</th>
                <th colspan="3">Pengeluaran</th>
                <th colspan="3">Persediaan (Akhir)</th>
                <th rowspan="2" style="width: 5%;">Sumber</th>
                <th rowspan="2" style="width: 7%;">Supplier</th>
            </tr>
            <tr>
                <th style="width: 3%;">Jml</th><th style="width: 5%;">Harga</th><th style="width: 6%;">Total</th>
                <th style="width: 3%;">Jml</th><th style="width: 5%;">Harga</th><th style="width: 6%;">Total</th>
                <th style="width: 3%;">Jml</th><th style="width: 5%;">Harga</th><th style="width: 6%;">Total</th>
                <th style="width: 3%;">Jml</th><th style="width: 5%;">Harga</th><th style="width: 6%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stoks as $item)
                @php
                    $awal_jml = $item->jumlah + $item->jumlah_keluar - $item->jumlah_masuk;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->obat->kategori->nama_kategori ?? '-' }}</td>
                    <td style="font-weight: bold;">{{ $item->obat->nama_obat ?? '-' }}</td>
                    <td class="text-center">{{ $item->obat->satuan->nama_satuan ?? '-' }}</td>
                    <td class="text-center">{{ $item->batch_number }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->translatedFormat('d/m/y') }}</td>

                    <td class="text-center">{{ number_format($item->jumlah + ($item->jumlah_keluar ?? 0) - ($item->jumlah_masuk ?? 0), 0, ',', '.') }}</td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ number_format($item->harga, 0, ',', '.') }}</td></tr></table>
                    </td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ number_format(($item->jumlah + ($item->jumlah_keluar ?? 0) - ($item->jumlah_masuk ?? 0)) * $item->harga, 0, ',', '.') }}</td></tr></table>
                    </td>

                    <td class="text-center">{{ $item->jumlah_masuk ?: '-' }}</td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ $item->harga_masuk > 0 ? number_format($item->harga_masuk, 0, ',', '.') : '-' }}</td></tr></table>
                    </td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ $item->total_masuk > 0 ? number_format($item->total_masuk, 0, ',', '.') : '-' }}</td></tr></table>
                    </td>

                    <td class="text-center">{{ $item->jumlah_keluar ?: '-' }}</td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ $item->harga_keluar > 0 ? number_format($item->harga_keluar, 0, ',', '.') : '-' }}</td></tr></table>
                    </td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ $item->total_keluar > 0 ? number_format($item->total_keluar, 0, ',', '.') : '-' }}</td></tr></table>
                    </td>

                    <td class="text-center">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ number_format($item->harga, 0, ',', '.') }}</td></tr></table>
                    </td>
                    <td>
                        <table class="acc-table"><tr><td class="acc-rp">Rp</td><td class="acc-val">{{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</td></tr></table>
                    </td>

                    <td class="text-center" style="font-size: 5.5pt;">{{ $item->sumberAnggaran->nama_anggaran ?? '-' }}</td>
                    <td style="font-size: 5.5pt;">{{ $item->supplier->nama_supplier ?? 'N/A' }}</td>
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