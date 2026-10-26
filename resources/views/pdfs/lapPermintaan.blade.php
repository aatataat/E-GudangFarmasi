<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Detail Permintaan Barang</title>
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
        /* TABEL DATA (Optimasi 10 Kolom Portrait) */
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
            font-size: 8pt; /* Dioptimalkan agar muat 10 kolom */
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Alokasi Lebar Kolom Proporsional (Total 100%) */
        .col-no { width: 4%; }
        .col-no-surat { width: 14%; }
        .col-tgl { width: 11%; }
        .col-faskes { width: 14%; }
        .col-status { width: 9%; }
        .col-nama { width: 15%; }
        .col-kat { width: 11%; }
        .col-satuan { width: 8%; }
        .col-diminta { width: 7%; }
        .col-diberi { width: 7%; }

        /* Status Text Utilities (Pengganti Class Bootstrap Badge) */
        .status-diterima { font-weight: bold; color: #155724; }
        .status-ditolak { font-weight: bold; color: #721c24; }
        .status-diajukan { font-weight: bold; color: #856404; }

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
        <h3 style="margin: 0; font-weight: bold; font-size: 13pt;">LAPORAN DETAIL ITEM PERMINTAAN BARANG</h3>
        <p style="margin: 0;">Per {{ now()->timezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-no-surat">No. Permintaan</th>
                <th class="col-tgl">Tgl. Permintaan</th>
                <th class="col-faskes">Faskes Peminta</th>
                <th class="col-status">Status</th>
                <th class="col-nama">Nama Barang</th>
                <th class="col-kat">Kategori</th>
                <th class="col-satuan">Satuan</th>
                <th class="col-diminta">Jml Minta</th>
                <th class="col-diberi">Jml Beri</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permintaanItems as $key => $item)
                <tr>
                    {{-- 1. Nomor Urut Berdasarkan Pagination --}}
                    <td style="text-align: center;">{{ $key + 1 }}</td> 

                    {{-- 2. No. Permintaan --}}
                    <td>{{ $item->permintaan->nomor_surat ?? '-' }}</td>

                    {{-- 3. Tgl. Permintaan --}}
                    <td class="text-center">
                        {{ isset($item->permintaan->tanggal_surat) ? \Carbon\Carbon::parse($item->permintaan->tanggal_surat)->translatedFormat('d F Y') : '-' }}
                    </td>

                    {{-- 4. Faskes / Puskesmas Peminta --}}
                    <td>{{ $item->permintaan->faskes->nama_faskes ?? '-' }}</td>

                    {{-- 5. Status Permintaan (Disederhanakan text style agar rapi di PDF tanpa box tebal) --}}
                    <td class="text-center">
                        @if (($item->permintaan->status ?? '') === 'DITERIMA' || ($item->permintaan->status ?? '') === 'SELESAI')
                            <span class="status-diterima">DITERIMA</span>
                        @elseif(($item->permintaan->status ?? '') === 'DITOLAK')
                            <span class="status-ditolak">DITOLAK</span>
                        @else
                            <span class="status-diajukan">DIAJUKAN</span>
                        @endif
                    </td>

                    {{-- 6. Nama Barang --}}
                    <td>{{ $item->stok->obat->nama_obat ?? '-' }}</td>

                    {{-- 7. Kategori Barang --}}
                    <td>{{ $item->stok->obat->kategori->nama_kategori ?? '-' }}</td>

                    {{-- 8. Satuan Barang --}}
                    <td class="text-center">{{ $item->stok->obat->satuan->nama_satuan ?? '-' }}</td>

                    {{-- 9. Jumlah Diminta --}}
                    <td class="text-right">
                        {{ number_format($item->jumlah_diminta, 0, ',', '.') }}
                    </td>

                    {{-- 10. Jumlah Diberi --}}
                    <td class="text-right">
                        @if (($item->permintaan->status ?? '') === 'DITERIMA' || ($item->permintaan->status ?? '') === 'SELESAI')
                            {{ number_format($item->jumlah_diberi, 0, ',', '.') }}
                        @elseif (($item->permintaan->status ?? '') === 'DITOLAK')
                            -
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Data Item Permintaan Tidak Ditemukan</td>
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