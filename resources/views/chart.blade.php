<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Sensor</title>
    <link href="{{ asset('/images/logo.png') }}" rel="shortcut icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #81C784, #4CAF50);
            font-family: "Prompt", sans-serif;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <h1 class="text-white mb-4">Dashboard - ค่าต่าง ๆ</h1>
        <div class="row">

            <!-- ความชื้นภายนอก -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">ความชื้นภายนอก</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="humOutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ความชื้นภายใน -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">ความชื้นภายใน</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="humInChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- อุณหภูมิภายนอก -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">อุณหภูมิภายนอก</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="tempOutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- อุณหภูมิภายใน -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">อุณหภูมิภายใน</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="tempInChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAN -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">TAN</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="tanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NH3 -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">NH3</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="nh3Chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function createChart(ctxId, label, color, dataValues) {
            const ctx = document.getElementById(ctxId);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: label,
                        data: dataValues,
                        borderColor: color,
                        backgroundColor: color + '33',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        createChart('humOutChart', 'ความชื้นภายนอก (%)', '#2196F3', @json($humid_out));
        createChart('humInChart', 'ความชื้นภายใน (%)', '#4CAF50', @json($humid_in));
        createChart('tempOutChart', 'อุณหภูมิภายนอก (°C)', '#FF9800', @json($temp_out));
        createChart('tempInChart', 'อุณหภูมิภายใน (°C)', '#E91E63', @json($temp_in));
        createChart('tanChart', 'TAN (mg/L)', '#9C27B0', @json($tan));
        createChart('nh3Chart', 'NH3 (ppm)', '#795548', @json($nh3));
    </script>

</body>

</html>
