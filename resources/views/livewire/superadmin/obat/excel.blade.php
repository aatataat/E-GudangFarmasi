<div wire:ignore.self class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: rgb(19, 172, 19)">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    
                    Import {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="import" class="mb-4 p-3 border rounded shadow-sm">
                <h5 class="text-dark mb-3">Silahkan Upload File</h5>
                <div class="form-group">
                    <label for="excelFile" class="text-dark">Pilih File Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" wire:model="file" class="form-control-file" id="excelFile">

                </div>
                <button type="submit" class="btn btn-info btn-sm" wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M11 10l0 0l2 -2l-3.5 3.5l-3.5 3.5l2 -2l0 0z">
                            <animate fill="freeze" attributeName="d" begin="0.8s" dur="0.2s"
                                values="M11 10l0 0l2 -2l-3.5 3.5l-3.5 3.5l2 -2l0 0z;M11 10l3 3l2 -2l0 7l-7 0l2 -2l-3 -3Z" />
                        </path>
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path stroke-dasharray="64" stroke-dashoffset="64"
                                d="M13.5 3l5.5 5.5v11.5c0 0.55 -0.45 1 -1 1h-12c-0.55 0 -1 -0.45 -1 -1v-16c0 -0.55 0.45 -1 1 -1Z">
                                <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s"
                                    values="64;0" />
                            </path>
                            <path d="M14.5 3.5l2.25 2.25l2.25 2.25z" opacity="0">
                                <animate fill="freeze" attributeName="d" begin="0.6s" dur="0.2s"
                                    values="M14.5 3.5l2.25 2.25l2.25 2.25z;M14.5 3.5l0 4.5l4.5 0z" />
                                <set fill="freeze" attributeName="opacity" begin="0.6s" to="1" />
                            </path>
                        </g>
                    </svg>
                    Import Data
                </button>
                <div class="mt-4">
                    <span> Hasil:
                    </span>
                </div>
                
                {{-- Notifikasi Sukses --}}
                @if (session()->has('message'))
                    <div class="alert alert-success mt-3" x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show"
                        x-transition.duration.500ms>
                        {{ session('message') }}
                    </div>
                @endif
                {{-- Notifikasi Gagal --}}
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
            </form>
        </div>
    </div>
</div>
