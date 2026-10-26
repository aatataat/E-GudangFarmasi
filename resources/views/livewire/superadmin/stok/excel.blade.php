<div wire:ignore.self class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: rgb(19, 172, 19)">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M13.641 21.162c0 1.443 1.563 2.345 2.813 1.624c.58-.335.938-.955.938-1.625c0-1.443-1.563-2.345-2.813-1.624c-.58.336-.938.955-.938 1.625m-10.314 0c0 1.443 1.563 2.345 2.813 1.624c.58-.335.938-.955.938-1.625c0-1.443-1.563-2.345-2.813-1.624c-.58.336-.938.955-.938 1.625m16.966-4.005a1.865 1.865 0 0 1-1.856 1.66H1.022a.977.977 0 0 1 0-1.914h16.604a.98.98 0 0 0 .977-.83l1.514-13.41a1.866 1.866 0 0 1 1.856-1.66h.537a.977.977 0 1 1 0 1.875h-.362a.234.234 0 0 0-.234.205Z" />
                                    <path fill="currentColor"
                                        d="M1.92 11.785h6.564s.938 0 .938.938v1.875s0 .938-.938.938H1.921s-.938 0-.938-.938v-1.875s0-.938.938-.938m2.813-4.219h3.75s.938 0 .938.937v.938s0 .938-.938.938h-3.75s-.938 0-.938-.938v-.938s0-.937.938-.937m7.032-2.344h4.688s.938 0 .938.937v8.44s0 .937-.938.937h-4.688s-.938 0-.938-.938V6.16s0-.937.938-.937" />
                                </svg>
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
