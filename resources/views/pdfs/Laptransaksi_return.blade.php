<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Transaksi Return</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            margin: 15px;
        }

        /* HEADER & KOP SURAT */
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

        /* TABEL DATA */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            word-wrap: break-word;
            vertical-align: top;
            font-size: 8.5pt;
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
            vertical-align: middle;
        }

        /* Alokasi Lebar Kolom (Disesuaikan untuk Portrait) */
        .col-no { width: 5%; }
        .col-faskes { width: 15%; }
        .col-tgl { width: 12%; }
        .col-kat { width: 12%; }
        .col-nama { width: 20%; }
        .col-batch { width: 10%; }
        .col-jml { width: 8%; }
        .col-status { width: 18%; }

        .footer { margin-top: 30px; text-align: right; }
        .signature-block { display: inline-block; width: 250px; text-align: center; }
        .footer p { margin: 0; line-height: 1.5; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>

<body>

    <div class="header-container">
        <div class="logo-column">
            @php
                $logoPath = public_path('img/logo.png');
                $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
            @endphp
            @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
            @endif
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
        <h3 style="margin: 0; font-weight: bold;">LAPORAN TRANSAKSI RETURN BARANG</h3>
        <p style="margin: 0;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-faskes">Dari Faskes</th>
                <th class="col-tgl">Tgl Return</th>
                <th class="col-kat">Kategori</th>
                <th class="col-nama">Nama Barang</th>
                <th class="col-batch">No. Batch</th>
                <th class="col-jml">Jml</th>
                <th class="col-status">Status Stok / Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksiReturns as $item)
                <tr>
                    <td class="text-center">
                        @if (method_exists($transaksiReturns, 'currentPage'))
                            {{ $loop->iteration + ($transaksiReturns->currentPage() - 1) * $transaksiReturns->perPage() }}
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </td>
                    <td>{{ $item->transaksiKeluar->permintaan->faskes->nama_faskes ?? '-' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_return)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->transaksiKeluar->stok->obat->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->transaksiKeluar->stok->obat->nama_obat ?? '-' }}</td>
                    <td class="text-center">{{ $item->transaksiKeluar->batch_number ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $item->status_stok }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data Transaksi Return Tidak Ditemukan</td>
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