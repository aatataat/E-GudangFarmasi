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
            margin: 5px 0 15px 0;
            height: 1px;
        }

        /* ================================================= */
        /* TABEL DATA (Optimasi 10 Kolom) */
        /* ================================================= */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            word-wrap: break-word;
            vertical-align: top;
            font-size: 7.5pt;
            /* Font sedikit diperkecil karena kolom bertambah */
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
            vertical-align: middle;
        }

        /* Lebar Kolom Proposional (Disesuaikan untuk 10 kolom) */
        .col-no {
            width: 3%;
        }

        .col-kat {
            width: 10%;
        }

        .col-nama {
            width: 15%;
        }

        .col-batch {
            width: 8%;
        }

        .col-jml {
            width: 5%;
        }

        .col-harga {
            width: 10%;
        }

        .col-total {
            width: 11%;
        }

        .col-ed {
            width: 8%;
        }

        .col-anggaran {
            width: 12%;
        }

        .col-supplier {
            width: 18%;
        }

        /* Format Akuntansi */
        .acc-container {
            display: table;
            width: 100%;
        }

        .acc-rp {
            display: table-cell;
            text-align: left;
            width: 15px;
        }

        .acc-val {
            display: table-cell;
            text-align: right;
        }

        /* ================================================= */
        /* FOOTER */
        /* ================================================= */
        .footer {
            margin-top: 30px;
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

    <div class="document-title" style="text-align: center; margin-bottom: 10px;">
        <h3 style="margin: 0; font-weight: bold;">LAPORAN STOK BARANG</h3>
        <p style="margin: 0;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
    <tr>
        <th rowspan="2" class="col-no">No</th>
        <th rowspan="2" class="col-kat">Kategori Barang</th>
        <th rowspan="2" class="col-nama">Nama Barang</th>
        <th rowspan="2" class="col-satuan">Satuan</th>
        <th rowspan="2" class="col-batch">Batch Number</th>
        <th colspan="3" class="text-center">Stok</th>
        <th rowspan="2" class="col-ed">Tanggal Kedaluwarsa</th>
        <th rowspan="2" class="col-anggaran">Sumber Anggaran</th>
        <th rowspan="2" class="col-penyedia">Penyedia</th>
    </tr>
    <tr>
        <th class="col-stok">Jumlah Satuan</th>
        <th class="col-stok">Nilai Satuan</th>
        <th class="col-stok">Total</th>
    </tr>
</thead>
<tbody>
    @forelse ($stoks as $key => $item) 
    <tr>
        <td style="text-align: center;">{{ $key + 1 }}</td> 
        <td>{{ $item->obat->kategori->nama_kategori ?? '-' }}</td>
        <td>{{ $item->obat->nama_obat ?? '-' }}</td>
        <td style="text-align: center;">{{ $item->obat->satuan->nama_satuan ?? '-' }}</td>
        <td style="text-align: center;">{{ $item->batch_number ?? '-' }}</td>
        
        {{-- Kolom di bawah Grup Stok --}}
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

        {{-- Kolom setelah Stok --}}
        <td style="text-align: center;">
            {{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->translatedFormat('d F Y') }}
        </td>
        <td>{{ $item->sumberAnggaran->nama_anggaran ?? '-' }}</td>
        <td>{{ $item->supplier->nama_supplier ?? '-' }}</td> {{-- Kolom Penyedia --}}
    </tr>
    @empty
    <tr>
        <td colspan="11" style="text-align: center;">Data tidak ditemukan</td>
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
