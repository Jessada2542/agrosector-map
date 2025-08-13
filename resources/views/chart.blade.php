<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Sensor Data Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div style="width:90%;margin:auto;">

    <h2>รวม Temp + Humid + CO₂</h2>
    <canvas id="allChart"></canvas>

    <h2>Temperature</h2>
    <canvas id="tempChart"></canvas>

    <h2>Humidity</h2>
    <canvas id="humidChart"></canvas>

    <h2>CO₂</h2>
    <canvas id="co2Chart"></canvas>

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
                {
                    label: 'Temperature (°C)',
                    data: tempData,
                    borderColor: 'rgba(255,99,132,1)',
                    backgroundColor: 'rgba(255,99,132,0.2)',
                    fill: false,
                    yAxisID: 'y'
                },
                {
                    label: 'Humidity (%)',
                    data: humidData,
                    borderColor: 'rgba(54,162,235,1)',
                    backgroundColor: 'rgba(54,162,235,0.2)',
                    fill: false,
                    yAxisID: 'y'
                },
                {
                    label: 'CO₂ (ppm)',
                    data: co2Data,
                    borderColor: 'rgba(75,192,192,1)',
                    backgroundColor: 'rgba(75,192,192,0.2)',
                    fill: false,
                    yAxisID: 'y1'
                }
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
        data: {
            labels: labels,
            datasets: [{
                label: 'Temperature (°C)',
                data: tempData,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: false } } }
    });

    // Chart Humid
    new Chart(document.getElementById('humidChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Humidity (%)',
                data: humidData,
                borderColor: 'rgba(54,162,235,1)',
                backgroundColor: 'rgba(54,162,235,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    // Chart CO₂
    new Chart(document.getElementById('co2Chart').getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'CO₂ (ppm)',
                data: co2Data,
                borderColor: 'rgba(75,192,192,1)',
                backgroundColor: 'rgba(75,192,192,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: false } } }
    });
</script>
</body>
</html>
