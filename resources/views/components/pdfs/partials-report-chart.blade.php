<div class="reportGraph">
    HELLO
    <canvas id="canvas{{ $date->format('d-m-Y') }}"></canvas>

</div>


<script>
    new Chart(
        document.getElementById("canvas{{ $date->format('d-m-Y') }}"), {
            "responsive": false,
            "type": "line",
            "data": {
                "datasets": [{
                    "label": "Limit",
                    "data": @json($noiseData),
                    "borderColor": "rgba(255, 0, 0, 1)",
                    "pointRadius": 0,
                    "borderWidth": 2,
                    "fill": false,
                    "steppedLine": true
                }, {
                    "label": "LAeq 5min",
                    "data": [{
                            x: '{{ $date->format('Y-m-d') }}T07:00:00',
                            y: 80
                        },
                        {
                            x: '{{ $date->format('Y-m-d') }}T08:00:00',
                            y: 80
                        },
                        {
                            x: '{{ $date->format('Y-m-d') }}T09:00:00',
                            y: 80
                        }
                    ],

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
