<div>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="pdfExportForm" action="#" wire:submit.prevent="exportPdf">
                            <div style="position: relative; height:350px; width:100%" wire:ignore>
                                <canvas id="topRequestedChart"></canvas>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <button wire:click="exportPdf" class="btn btn-danger">
    <i class="fas fa-file-pdf"></i> Cetak Grafik PDF
</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let myChart;

        function renderTopRequestedChart() {
            const canvas = document.getElementById('topRequestedChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const labelsData = @json($chartLabels);
            const valuesData = @json($chartData);

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsData,
                    datasets: [{
                        label: 'Total Jumlah Diminta',
                        data: valuesData,
                        // ARRAY WARNA YANG BERBEDA UNTUK SETIAP BAR (10 BARANG)
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)', // Biru
                            'rgba(255, 99, 132, 0.7)', // Merah Merona
                            'rgba(75, 192, 192, 0.7)', // Hijau Toska
                            'rgba(255, 206, 86, 0.7)', // Kuning
                            'rgba(153, 102, 255, 0.7)', // Ungu
                            'rgba(255, 159, 64, 0.7)', // Oranye
                            'rgba(201, 203, 207, 0.7)', // Abu-abu Abu
                            'rgba(0, 204, 153, 0.7)', // Hijau Mint
                            'rgba(230, 115, 0, 0.7)', // Cokelat Oranye
                            'rgba(102, 153, 0, 0.7)' // Hijau Daun
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(201, 203, 207, 1)',
                            'rgba(0, 204, 153, 1)',
                            'rgba(230, 115, 0, 1)',
                            'rgba(102, 153, 0, 1)'
                        ],
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    indexAxis: 'y', // Tetap menggunakan opsi Landscape Horizontal Anda
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 0
                    },
                    plugins: {
                        legend: {
                            display: false // Menyembunyikan kotak label atas karena warnanya sudah berbeda-beda
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value.toLocaleString('id-ID');
                                }
                            }
                        },
                        y: {
                            ticks: {
                                autoSkip: false,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // Ekstrak canvas grafik menjadi gambar Base64 Data URL
            setTimeout(() => {
                const chartImageBase64 = canvas.toDataURL('image/png');
                // Kirim string gambar ini ke komponen backend Livewire PHP
                @this.set('chartImage', chartImageBase64);
            }, 100);
        }

        document.addEventListener('livewire:init', () => {
            renderTopRequestedChart();
        });

        document.addEventListener('livewire:navigated', () => {
            renderTopRequestedChart();
        });
    </script>
</div>
