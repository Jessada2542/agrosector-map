<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Sensor Data Charts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container py-4">

    <!-- Card กราฟรวม -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">กราฟรวม Temperature + Humidity + CO₂</h5>
        </div>
        <div class="card-body">
            <canvas id="allChart" style="height:350px;"></canvas>
        </div>
    </div>

    <!-- 3 กราฟแยก -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">Temperature (°C)</h6>
                </div>
                <div class="card-body">
                    <canvas id="tempChart" style="height:250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Humidity (%)</h6>
                </div>
                <div class="card-body">
                    <canvas id="humidChart" style="height:250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">CO₂ (ppm)</h6>
                </div>
                <div class="card-body">
                    <canvas id="co2Chart" style="height:250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    const labels = {!! json_encode($data->pluck('created_at')->map(fn($d)=>$d->format('H:i'))) !!};
    const tempData = {!! json_encode($data->pluck('temp')) !!};
    const humidData = {!! json_encode($data->pluck('humid')) !!};
    const co2Data = {!! json_encode($data->pluck('co2')) !!};

    // Chart รวม
    new Chart(document.getElementById('allChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Temperature (°C)', data: tempData, borderColor: 'rgba(255,99,132,1)', backgroundColor: 'rgba(255,99,132,0.2)', fill: false, yAxisID: 'y' },
                { label: 'Humidity (%)', data: humidData, borderColor: 'rgba(54,162,235,1)', backgroundColor: 'rgba(54,162,235,0.2)', fill: false, yAxisID: 'y' },
                { label: 'CO₂ (ppm)', data: co2Data, borderColor: 'rgba(75,192,192,1)', backgroundColor: 'rgba(75,192,192,0.2)', fill: false, yAxisID: 'y1' }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            stacked: false,
            scales: {
                y: { type: 'linear', position: 'left' },
                y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false } }
            }
        }
    });

    // Chart Temp
    new Chart(document.getElementById('tempChart').getContext('2d'), {
        type: 'line',
        data: { labels: labels, datasets: [{ label: 'Temperature (°C)', data: tempData, borderColor: 'rgba(255,99,132,1)', backgroundColor: 'rgba(255,99,132,0.2)', fill: true, tension: 0.3 }] },
        options: { responsive: true, scales: { y: { beginAtZero: false } } }
    });

    // Chart Humid
    new Chart(document.getElementById('humidChart').getContext('2d'), {
        type: 'line',
        data: { labels: labels, datasets: [{ label: 'Humidity (%)', data: humidData, borderColor: 'rgba(54,162,235,1)', backgroundColor: 'rgba(54,162,235,0.2)', fill: true, tension: 0.3 }] },
        options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    // Chart CO₂
    new Chart(document.getElementById('co2Chart').getContext('2d'), {
        type: 'line',
        data: { labels: labels, datasets: [{ label: 'CO₂ (ppm)', data: co2Data, borderColor: 'rgba(75,192,192,1)', backgroundColor: 'rgba(75,192,192,0.2)', fill: true, tension: 0.3 }] },
        options: { responsive: true, scales: { y: { beginAtZero: false } } }
    });
</script>

</body>
</html>
