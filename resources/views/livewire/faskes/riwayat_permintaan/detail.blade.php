<div wire:ignore.self class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Permintaan Logistik</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($permintaanDetail)
                @php
                    $badgeClassDetail = match ($permintaanDetail->status) {
                        'DIAJUKAN' => 'badge-warning',
                        'DITERIMA' => 'badge-primary',
                        'DIPROSES' => 'badge-info',
                        'DITOLAK' => 'badge-danger',
                        'SELESAI' => 'badge-success',
                        default => 'badge-secondary',
                    };

                    $sudahDiproses = in_array($permintaanDetail->status, ['DITERIMA', 'DIPROSES', 'SELESAI']);
                @endphp
                
                <div class="modal-body text-dark" style="color: #000 !important;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nomor Permintaan:</strong> <span class="text-dark">{{ $permintaanDetail->nomor_surat }}</span></p>
                            <p><strong>Faskes Pengaju:</strong>
                                <span class="text-dark">{{ $permintaanDetail->faskes->nama_faskes ?? 'N/A' }}</span></p>
                            <p><strong>Status:</strong> 
                                <span class="badge {{ $badgeClassDetail }}">{{ $permintaanDetail->status }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Perihal:</strong> <span class="text-dark">{{ $permintaanDetail->perihal }}</span></p>
                            <p><strong>Tanggal Permintaan:</strong>
                                <span class="text-dark">{{ $permintaanDetail->tanggal_surat->format('d M Y') }}</span></p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="font-weight-bold">Item yang Diminta:</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-dark">
                            <thead>
                                <tr class="bg-light">
                                    <th>#</th>
                                    <th>Nama Barang/Obat</th>
                                    <th class="text-center">Jumlah Diminta</th>
                                    @if (in_array($permintaanDetail->status, ['DITERIMA', 'SELESAI']))
                                        <th class="text-center bg-light">Jumlah Diberi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($itemsDetail as $i => $item)
                                    <tr>
                                        <td class="text-dark">{{ $i + 1 }}</td>
                                        <td class="text-dark font-weight-bold">{{ $item['nama_obat'] }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-warning text-dark">{{ $item['jumlah_diminta'] }}</span>
                                        </td>
                                        @if (in_array($permintaanDetail->status, ['DITERIMA', 'SELESAI']))
                                            <td class="text-center font-weight-bold">
                                                <span class="badge badge-primary">
                                                    {{ $item['jumlah_diberi'] }}
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-dark">Tidak ada item detail.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>