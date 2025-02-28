<div class="reportGraph"><canvas id="canvas{{ $date->format('d-m-Y') }}"></canvas></div>


<script>
    function generateLimitData() {
        const data = [];
        const start = new Date('{{ $date->format('Y-m-d') }}T07:00:00');
        const end = new Date(start);
        end.setDate(end.getDate() + 1);
        end.setMinutes(end.getMinutes() - 1);

        for (var time = start; time <= end; time.setMinutes(time.getMinutes() + 5)) {
            const dayOfWeek = time.getDay();
            const isWeekend = (dayOfWeek === 0);
            const hours = time.getHours();
            var yValue;

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
                x: new Date(time),
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

        for (var time = start; time <= end; time.setMinutes(time.getMinutes() + 5)) {
            data.push({
                x: new Date(time),
                y: NaN
            });
        }

        @foreach ($noiseData as $item)
            var receiveAt = new Date('{{ $item->received_at }}');

            for (var i = 0; i < data.length; i++) {
                console.log(receiveAt);
                if (data[i].x === receiveAt) {
                    console.log("repeated");
                    console.log(data[i].x));
                    data[i].y = {{ $item->leq }};
                    break;
                }
            }
        @endforeach

    return data;
    }


    new Chart(
        document.getElementById("canvas{{ $date->format('d-m-Y') }}"), {
            "responsive": false,
            "type": "line",
            "data": {
                "datasets": [{
                    "label": "Limit",
                    "data": generateLimitData(),
                    "borderColor": "rgba(255, 0, 0, 1)",
                    "pointRadius": 0,
                    "borderWidth": 2,
                    "fill": false,
                    "steppedLine": true
                }, {
                    "label": "LAeq 5min",
                    "data": generateNoiseData(),

                    "borderColor": "rgba(0, 0, 255, 1)",
                    "borderWidth": 2,
                    "pointRadius": 0,
                    "spanGaps": true,
                    "fill": false
                }]
            },
            "options": {
                "scales": {
                    "xAxes": [{
                        "type": "time",
                        "time": {
                            "unit": "hour",
                            "stepSize": 1,
                            "min": "{{ $date->format('Y-m-d') }}T07:00:00",
                            "max": "{{ $date->copy()->addDay()->format('Y-m-d') }}T06:55:00",
                            "displayFormats": {
                                "hour": "HH:mm"
                            }
                        },
                        "ticks": {
                            "autoSkip": true,
                            "maxTicksLimit": 24
                        }
                    }],
                    "yAxes": [{
                        "ticks": {
                            "beginAtZero": true,
                            "max": 120
                        }
                    }]
                },
                "legend": {
                    "display": true
                }
            }
        });
</script>
