<div class="reportGraph">
    <canvas id="myChart{{ $date->format('d-m-Y') }}"></canvas>
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
            let yValue;

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
        responsive: false,
        type: 'line',
        data: {
            datasets: [{
                label: 'Limit',
                data: generateLimitData(),
                borderColor: 'rgba(255, 0, 0, 1)',
                pointRadius: 0,
                borderWidth: 2,
                fill: false,
                steppedLine: true
            }, {
                label: 'LAeq 5min',
                data: generateNoiseData(),
                borderColor: 'rgba(0, 0, 255, 1)',
                borderWidth: 2,
                pointRadius: 0,
                spanGaps: true,
                fill: false
            }]
        },
        options: {
            scales: {
                xAxes: [{
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
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: 120
                    }
                }]
            },
            legend: {
                display: true
            }
        }

    });
</script>
