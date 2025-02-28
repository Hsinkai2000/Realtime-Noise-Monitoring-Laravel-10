<div>
    <canvas id="myChart{{ $date->format('d-m-Y') }}" height="80" style="border: 1px solid; padding: 10px;"></canvas>
</div>

<script>
    function generateLimitData() {
        const data = [];
        const start = new Date('{{ $date->format('Y-m-d') }}T07:00:00');
        const end = new Date(start);
        end.setDate(end.getDate() + 1);
        end.setMinutes(end.getMinutes() - 1);

        for (let time = start; time <= end; time.setMinutes(time.getMinutes() + 5)) {
            const dayOfWeek = time.getDay();
            const isWeekend = (dayOfWeek === 0);
            const hours = time.getHours();
            if (!isWeekend) {
                if (hours >= 7 && hours < 19) {
                    yValue = {{ $measurementPoint->soundLimit->mon_sat_7am_7pm_leq5min }};
                } else if (hours >= 19 && hours < 22) {
                    yValue = {{ $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq5min }};
                } else if (hours >= 22 && hours < 24) {
                    yValue = {{ $measurementPoint->soundLimit->mon_sat_10pm_12am_leq5min }};
                } else {
                    yValue = {{ $measurementPoint->soundLimit->mon_sat_12am_7am_leq5min }};
                }
            } else {
                if (hours >= 7 && hours < 19) {
                    yValue = {{ $measurementPoint->soundLimit->sun_ph_7am_7pm_leq5min }};
                } else if (hours >= 19 && hours < 22) {
                    yValue = {{ $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq5min }};
                } else if (hours >= 22 && hours < 24) {
                    yValue = {{ $measurementPoint->soundLimit->sun_ph_10pm_12am_leq5min }};
                } else {
                    yValue = {{ $measurementPoint->soundLimit->sun_ph_12am_7am_leq5min }};
                }
            }

            data.push({
                x: new Date(time).toISOString(),
                y: yValue
            });
        }

        return data;
    }

    // Generate second dataset
    function generateNoiseData() {
        const data = [];
        const start = new Date('{{ $date->format('Y-m-d') }}T07:00:00');
        const end = new Date(start);
        end.setDate(end.getDate() + 1);
        end.setMinutes(end.getMinutes() - 1);

        for (let time = start; time <= end; time.setMinutes(time.getMinutes() + 5)) {
            data.push({
                x: new Date(time).toISOString(),
                y: NaN
            });
        }

        // Populate data array with second dataset
        @foreach ($noiseData as $item)
            var receiveAt = new Date('{{ $item->received_at }}');
            var receiveAtISO = receiveAt.toISOString();

            for (let i = 0; i < data.length; i++) {
                if (data[i].x === receiveAtISO) {
                    data[i].y = {{ $item->leq }};
                    break;
                }
            }
        @endforeach

        return data;
    }

    var ctx = document.getElementById('myChart{{ $date->format('d-m-Y') }}').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Leq5Max',
                data: generateLimitData(),
                borderColor: 'rgba(255, 99, 132, 1)',
                pointRadius: 0,
                borderWidth: 2,
                fill: false,
                stepped: true
            }, {
                label: 'Noise Data',
                data: generateNoiseData(),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                cubicInterpolationMode: 'monotone',
                tension: 0.4,
                pointRadius: 0,
                fill: false,
                spanGaps: true
            }],
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'minute',
                        stepSize: 5,
                        displayFormats: {
                            minute: 'HH:mm'
                        }
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 8
                    },
                    min: '{{ $date->format('Y-m-d') }}T07:00:00',
                    max: (new Date(new Date('{{ $date->format('Y-m-d') }}T07:00:00').getTime() + (23 * 60 +
                        59) * 60 * 1000)).toISOString()
                },
                y: {
                    beginAtZero: true,
                    max: {{ $measurementPoint->soundLimit->get_max_leq5_limit($date) * 1.2 }}
                }
            },
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
</script>
