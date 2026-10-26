<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Akan Kedaluwarsa 3 Bulan Kedepan</title>
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
        /* TABEL DATA BERTINGKAT (Portrait - Tanpa Warna) */
        /* ================================================= */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            word-wrap: break-word;
            vertical-align: middle;
            font-size: 8.5pt;
        }

        .data-table th {
            text-align: center;
            background-color: transparent; /* Tanpa warna sesuai permintaan */
            font-weight: bold;
        }

        /* Alokasi Lebar Kolom Proporsional Portrait (Total 100%) */
        .col-no { width: 5%; }
        .col-kat { width: 15%; }
        .col-nama { width: 22%; }
        .col-batch { width: 11%; }
        .col-ed { width: 14%; }
        .col-jml { width: 9%; }
        .col-harga { width: 11%; }
        .col-total { width: 13%; }

        /* Format Akuntansi Stabil untuk DomPDF */
        .acc-table { width: 100%; border: none !important; margin: 0; padding: 0; }
        .acc-table td { border: none !important; padding: 0 1px !important; font-size: 8.5pt; }
        .acc-rp { text-align: left; width: 15px; }
        .acc-val { text-align: right; }

        .footer { margin-top: 35px; text-align: right; }
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

    <div class="document-title" style="text-align: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-weight: bold; font-size: 13pt;">LAPORAN BARANG AKAN KEDALUWARSA 3 BULAN KE DEPAN</h3>
        <p style="margin: 0;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">No</th>
                <th class="col-kat" rowspan="2">Kategori</th>
                <th class="col-nama" rowspan="2">Nama Obat</th>
                <th class="col-batch" rowspan="2">Batch Number</th>
                <th class="col-ed" rowspan="2">Tanggal Kedaluarsa</th>
                <th colspan="3">Persediaan</th>
            </tr>
            <tr>
                <th class="col-jml">Jumlah Satuan</th>
                <th class="col-harga">Nilai Satuan</th>
                <th class="col-total">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stoks as $key => $item)
                <tr>
                    {{-- 1. Nomor Urut Berdasarkan Pagination --}}
                    <td style="text-align: center;">{{ $key + 1 }}</td> 

                    {{-- 2. Kategori --}}
                    <td>{{ $item->obat->kategori->nama_kategori ?? '-' }}</td>

                    {{-- 3. Nama Barang --}}
                    <td>{{ $item->obat->nama_obat ?? '-' }}</td>

                    {{-- 4. No Batch --}}
                    <td class="text-center">{{ $item->batch_number ?? '-' }}</td>

                    {{-- 5. Tanggal Kadaluarsa --}}
                    <td class="text-center">
                        {{ isset($item->tanggal_kadaluarsa) ? \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->translatedFormat('d F Y') : '-' }}
                    </td>

                    {{-- 6. Jumlah Satuan --}}
                    <td class="text-center">
                        {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>

                    {{-- 7. Nilai Satuan (Harga) --}}
                    <td>
                        <table class="acc-table">
                            <tr>
                                <td class="acc-rp">Rp</td>
                                <td class="acc-val">{{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>

                    {{-- 8. Total --}}
                    <td>
                        <table class="acc-table">
                            <tr>
                                <td class="acc-rp">Rp</td>
                                <td class="acc-val">{{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data Persediaan Barang Tidak Ditemukan</td>
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