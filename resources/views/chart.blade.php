<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Sensor Data Charts</title>
    <link href="{{ asset('/images/logo.png') }}" rel="shortcut icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-light">

    <div class="container py-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">อุณหภูมิภายใน + อุณหภูมิภายนอก</h5>
            </div>
            <div class="card-body">
                <canvas id="allChart" style="height:350px;"></canvas>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">อุณหภูมิภายใน (°C)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="tempInChart" style="height:250px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">อุณหภูมิภายนอก (°C)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="tempOutChart" style="height:250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const labels = {!! json_encode($labels) !!};
        const tempInData = {!! json_encode($data->pluck('temp_in')) !!};
        const tempOutData = {!! json_encode($data->pluck('temp_out')) !!};

        // Chart รวม
        new Chart(document.getElementById('allChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'อุณหภูมิภายใน (°C)',
                        data: tempInData,
                        borderColor: 'rgba(255,99,132,1)',
                        backgroundColor: 'rgba(255,99,132,0.2)',
                        fill: false,
                        yAxisID: 'y'
                    },
                    {
                        label: 'อุณหภูมิภายนอก (°C)',
                        data: tempOutData,
                        borderColor: 'rgba(54,162,235,1)',
                        backgroundColor: 'rgba(54,162,235,0.2)',
                        fill: false,
                        yAxisID: 'y'
                    },
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                stacked: false,
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left'
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });

        // Chart TempIn
        new Chart(document.getElementById('tempInChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Temperature (°C)',
                    data: tempInData,
                    borderColor: 'rgba(255,99,132,1)',
                    backgroundColor: 'rgba(255,99,132,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        // Chart TempOut
        new Chart(document.getElementById('tempOutChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Temperature (°C)',
                    data: tempOutData,
                    borderColor: 'rgba(54,162,235,1)',
                    backgroundColor: 'rgba(54,162,235,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>

</body>

</html>
