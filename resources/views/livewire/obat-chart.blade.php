<div wire:ignore>
    <div class="mb-3 d-flex align-items-center" style="gap: 10px;">
        <label for="filterTahun" class="mb-0 fw-bold text-secondary" style="font-size: 14px;">Filter Tahun:</label>
        <select id="filterTahun" wire:model.live="tahun" class="form-control form-control-sm" style="width: 120px; border-radius: 4px;">
            @foreach($daftarTahun as $th)
                <option value="{{ $th }}">{{ $th }}</option>
            @endforeach
        </select>
    </div>

    <div class="chart-container" style="position: relative; height:350px; width:100%">
        <canvas id="obatChart"></canvas>
    </div>

    <script>
        function renderObatChart() {
            const ctx = document.getElementById('obatChart');
            if (!ctx) return;

            const existingChart = Chart.getChart(ctx);
            if (existingChart) { existingChart.destroy(); }

            // PENGAMAN: Cek apakah plugin datalabels tersedia di aplikasi
            const isPluginAvailable = typeof ChartDataLabels !== 'undefined';
            if (isPluginAvailable) {
                Chart.register(ChartDataLabels);
            }

            // Siapkan konfigurasi plugins dasar
            let chartPlugins = {
                legend: { 
                    display: true,
                    position: 'right'
                }
            };

            // Jika plugin tersedia, masukkan konfigurasi persentase
            if (isPluginAvailable) {
                chartPlugins.datalabels = {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += Number(data);
                        });
                        let percentage = (value * 100 / sum).toFixed(1) + "%";
                        return percentage;
                    }
                };
            }

            new Chart(ctx, {
                type: 'pie', 
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Total Keluar',
                        data: @json($data),
                        backgroundColor: [
                            'rgba(60, 141, 188, 0.8)',
                            'rgba(0, 192, 239, 0.8)',
                            'rgba(0, 166, 90, 0.8)',
                            'rgba(243, 156, 18, 0.8)',
                            'rgba(245, 105, 84, 0.8)',
                            'rgba(11, 227, 210, 0.8)',
                            'rgba(174, 122, 244, 0.8)',
                            'rgba(210, 214, 222, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: chartPlugins // Menggunakan config plugin yang sudah dinamis
                }
            });
        }

        document.addEventListener('livewire:navigated', renderObatChart);
        document.addEventListener('DOMContentLoaded', renderObatChart);

        Livewire.hook('component.init', ({ component }) => {
            if (component.name === 'obat-chart') {
                Livewire.hook('request.render', ({ component }) => {
                    setTimeout(() => {
                        renderObatChart();
                    }, 50);
                });
            }
        });
    </script>
</div>