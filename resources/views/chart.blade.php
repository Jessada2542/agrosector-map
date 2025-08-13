<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Prompt', sans-serif;
        background: #f5f5f5;
    }
    .card {
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    .card-header {
        font-weight: 600;
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

    <h1 class="mb-4">Dashboard</h1>

    <!-- ส่วน form เลือกวันที่ -->
    <form method="GET" class="mb-4 row g-2">
        <div class="col-auto">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-auto">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">ค้นหา</button>
        </div>
    </form>

    <div class="row">
        <!-- กราฟความชื้นรวม -->
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">ความชื้นรวม (%)</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="humCombinedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- กราฟอุณหภูมิรวม -->
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">อุณหภูมิรวม (°C)</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="tempCombinedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- กราฟแสงภายนอก -->
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">แสงภายนอก (lux)</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lightOutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- กราฟแสงภายใน -->
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">แสงภายใน (lux)</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lightInChart"></canvas>
                    </div>
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
            scales: { y: { beginAtZero: true } }
        }
    });
}

// เรียกสร้างกราฟ
createChart('humCombinedChart', 'ความชื้นรวม (%)', '#00BCD4', @json($humid_combined));
createChart('tempCombinedChart', 'อุณหภูมิรวม (°C)', '#FF5722', @json($temp_combined));
createChart('lightOutChart', 'แสงภายนอก (lux)', '#FFC107', @json($light_out));
createChart('lightInChart', 'แสงภายใน (lux)', '#8BC34A', @json($light_in));
</script>
</body>
</html>
