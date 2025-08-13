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
    <div class="container mt-4">
        <div class="mb-3">
            <input type="date" id="filterDate" class="form-control" />
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">All Data</div>
                    <div class="card-body"><canvas id="chartAll"></canvas></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">Temperature</div>
                    <div class="card-body"><canvas id="chartTemp"></canvas></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">Humidity</div>
                    <div class="card-body"><canvas id="chartHumid"></canvas></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">CO2</div>
                    <div class="card-body"><canvas id="chartCo2"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chartAll, chartTemp, chartHumid, chartCo2;

        async function loadData(date = '') {
            const res = await fetch(`/api/sensor/get/self?date=${date}`);
            const data = await res.json();

            const labels = data.map(r => r.created_at);
            const temps = data.map(r => r.temp);
            const humids = data.map(r => r.humid);
            const co2s = data.map(r => r.co2);

            if (!chartAll) {
                chartAll = new Chart(document.getElementById('chartAll'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                                label: 'Temp',
                                data: temps,
                                borderColor: 'red',
                                fill: false
                            },
                            {
                                label: 'Humidity',
                                data: humids,
                                borderColor: 'blue',
                                fill: false
                            },
                            {
                                label: 'CO2',
                                data: co2s,
                                borderColor: 'green',
                                fill: false
                            },
                        ]
                    },
                    options: {
                        responsive: true
                    }
                });

                chartTemp = new Chart(document.getElementById('chartTemp'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Temp',
                            data: temps,
                            borderColor: 'red',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });

                chartHumid = new Chart(document.getElementById('chartHumid'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Humidity',
                            data: humids,
                            borderColor: 'blue',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });

                chartCo2 = new Chart(document.getElementById('chartCo2'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'CO2',
                            data: co2s,
                            borderColor: 'green',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });

            } else {
                [chartAll, chartTemp, chartHumid, chartCo2].forEach(c => {
                    c.data.labels = labels;
                });
                chartAll.data.datasets[0].data = temps;
                chartAll.data.datasets[1].data = humids;
                chartAll.data.datasets[2].data = co2s;
                chartTemp.data.datasets[0].data = temps;
                chartHumid.data.datasets[0].data = humids;
                chartCo2.data.datasets[0].data = co2s;

                [chartAll, chartTemp, chartHumid, chartCo2].forEach(c => c.update());
            }
        }

        loadData();

        document.getElementById('filterDate').addEventListener('change', (e) => {
            loadData(e.target.value);
        });
    </script>


</body>

</html>
