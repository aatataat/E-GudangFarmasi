<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Transaksi Masuk</title> {{-- Judul dokumen diubah --}}
    <style>
        /* ... CSS yang sama dari jawaban sebelumnya (font-size 10pt agar cukup) ... */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt; /* Dikecilkan agar 11 kolom muat */
            margin: 25px;
        }
        /* ... CSS Header, document-info, footer tetap sama ... */

        /* --- Perbaikan utama ada di sini untuk tabel data (Mengandung 11 Kolom) --- */
        .data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 4px 6px; /* Padding dikecilkan */
            word-break: break-all;
            overflow-wrap: break-word;
        }
        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }
        /* Penyesuaian lebar kolom (disesuaikan agar total 100%) */
        .data-table td:first-child { width: 4%; text-align: center; } /* No */
        .data-table td:nth-child(2) { width: 9%; } /* Nomor Transaksi */
        .data-table td:nth-child(3) { width: 17%; } /* Nama Obat */
        .data-table td:nth-child(4) { width: 8%; text-align: center; } /* Tgl Masuk */
        .data-table td:nth-child(5) { width: 8%; } /* No Batch */
        .data-table td:nth-child(6) { width: 8%; text-align: center; } /* Tgl Kadaluarsa */
        .data-table td:nth-child(7) { width: 5%; text-align: center; } /* Jml */
        .data-table td:nth-child(8) { width: 10%; text-align: right; } /* Harga */
        .data-table td:nth-child(9) { width: 11%; text-align: right; } /* Total */
        .data-table td:nth-child(10) { width: 10%; } /* Anggaran */
        .data-table td:nth-child(11) { width: 10%; } /* Supplier */
        /* Total lebar: 4 + 9 + 17 + 8 + 8 + 8 + 5 + 10 + 11 + 10 + 10 = 100% */
        
        .footer {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KABUPATEN [NAMA KABUPATEN]</h2> 
        <p>DINAS [NAMA DINAS]</p> 
        <p>Jalan [Nama Jalan] Nomor [Nomor], [Nama Kota]</p> 
        <hr>
    </div>

    <div class="document-info">
        <div class="align-right">
            <p>[Nama Kota], {{ now()->timezone('Asia/Makassar')->format('d F Y') }}</p> 
        </div>
        <table>
            <tbody>
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>[Nomor Surat]</td> 
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td>Laporan Data Transaksi Masuk</td> {{-- Sesuai PDF --}}
                </tr>
            </tbody>
        </table>
    </div>

    <p style="margin-top: 40px;">Kepada Yth.,<br>
    Bapak/Ibu Pimpinan<br>
    Di tempat</p>

    <p style="text-align: justify; text-indent: 30px;">
        Dengan hormat,
        <br><br>
        Bersama surat ini, kami lampirkan **Laporan Data Transaksi Masuk Obat** per tanggal
        {{ now()->timezone('Asia/Makassar')->format('d F Y') }}.
        Data ini disajikan dalam format tabel sebagai berikut:
    </p>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Transaksi</th> 
                <th>Nama Obat</th> 
                <th>Tgl Masuk</th> 
                <th>No Batch</th> 
                <th>Tgl Kadaluarsa</th> 
                <th>Jml</th> 
                <th>Harga Satuan</th> 
                <th>Total</th> 
                <th>Anggaran</th> 
                <th>Supplier</th> 
            </tr>
        </thead>
        <tbody>
            {{-- Menggunakan variabel yang sama dengan yang dikirim di Livewire: $transaksi_masuk --}}
            @foreach ($transaksi_masuk as $key => $item) 
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->no }}</td>
                <td>{{ $item->obat->nama_obat ?? 'N/A' }}</td> 
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}</td>
                <td>{{ $item->batch_number }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $item->jumlah }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                <td>{{ $item->sumberAnggaran->nama_sumber ?? 'N/A' }}</td>
                <td>{{ $item->supplier->nama_supplier ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 40px; text-align: justify; text-indent: 30px;">
        Demikian laporan ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.
    </p>

    <div class="footer">
        <p>Hormat kami,</p>
        <br><br><br>
        <p>([Nama Pejabat])</p>
    </div>

</body>
</html>