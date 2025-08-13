<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sensor</title>
     <link href="{{ asset('/images/logo.png') }}" rel="shortcut icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background: #f5f5f5;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .border-purple {
            border-color: #6f42c1 !important;
        }

        .border-brown {
            border-color: #8B4513 !important;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .bg-brown {
            background-color: #8B4513 !important;
        }
    </style>
</head>

<body>
    <div class="container py-4">

        <h1 class="mb-4">Dashboard</h1>

        <!-- Form เลือกวันที่ -->
        <form method="GET" class="mb-4 row g-2">
            <div class="col-auto"><input type="date" name="from" class="form-control"
                    value="{{ request('from') }}"></div>
            <div class="col-auto"><input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-auto"><button class="btn btn-primary" type="submit">ค้นหา</button></div>
        </form>

        <div class="row">
            <!-- กราฟรวม (2 เส้น: ภายนอก/ภายใน) -->
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">ความชื้น (%)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="humCombinedChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">อุณหภูมิ (°C)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="tempCombinedChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-purple">
                    <div class="card-header bg-purple text-white">TAN (mg/L) - NH3 (ppm)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="tannh3CombinedChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">แสง (lux)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="lightCombinedChart"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- กราฟเดียว -->
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">ความชื้นภายนอก (%)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="humOutChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">ความชื้นภายใน (%)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="humInChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">อุณหภูมิภายนอก (°C)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="tempOutChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">อุณหภูมิภายใน (°C)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="tempInChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-purple">
                    <div class="card-header bg-purple text-white">TAN (mg/L)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="tanChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-brown">
                    <div class="card-header bg-brown text-white">NH3 (ppm)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="nh3Chart"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- กราฟแสง -->
            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">แสงภายนอก (lux)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="lightOutChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">แสงภายใน (lux)</div>
                    <div class="card-body">
                        <div class="chart-container"><canvas id="lightInChart"></canvas></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        function createMultiLineChart(ctxId, labels, datasets) {
            const ctx = document.getElementById(ctxId);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
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
        createChart('lightOutChart', 'แสงภายนอก (lux)', '#FFC107', @json($light_out));
        createChart('lightInChart', 'แสงภายใน (lux)', '#8BC34A', @json($light_in));

        // กราฟรวม (2 เส้น)
        createMultiLineChart('humCombinedChart', @json($labels), [{
                label: 'ภายนอก (%)',
                data: @json($humid_out),
                borderColor: '#2196F3',
                backgroundColor: '#2196F333',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            },
            {
                label: 'ภายใน (%)',
                data: @json($humid_in),
                borderColor: '#4CAF50',
                backgroundColor: '#4CAF5033',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            }
        ]);
        createMultiLineChart('tempCombinedChart', @json($labels), [{
                label: 'ภายนอก (°C)',
                data: @json($temp_out),
                borderColor: '#FF9800',
                backgroundColor: '#FF980033',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            },
            {
                label: 'ภายใน (°C)',
                data: @json($temp_in),
                borderColor: '#E91E63',
                backgroundColor: '#E91E6333',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            }
        ]);
        createMultiLineChart('tannh3CombinedChart', @json($labels), [{
                label: 'TAN (mg/L)',
                data: @json($tan),
                borderColor: '#9C27B0',
                backgroundColor: '#9C27B033',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            },
            {
                label: 'NH3 (ppm)',
                data: @json($nh3),
                borderColor: '#795548',
                backgroundColor: '#79554833',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            }
        ]);
        createMultiLineChart('lightCombinedChart', @json($labels), [{
                label: 'ภายนอก (lux)',
                data: @json($light_out),
                borderColor: '#FFC107',
                backgroundColor: '#FFC10733',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            },
            {
                label: 'ภายใน (lux)',
                data: @json($light_in),
                borderColor: '#8BC34A',
                backgroundColor: '#8BC34A33',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            }
        ]);
    </script>
</body>

</html>
