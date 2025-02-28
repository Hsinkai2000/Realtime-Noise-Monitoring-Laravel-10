<div class="reportGraph">
    <h1>hello</h1>
    <canvas id="canvas{{ $date->format('Y-m-d') }}"></canvas>
</div>


<script>
    function drawGraphs() {
        var canvas = document.getElementById('myChart' + dateStr).getContext('2d');
        new Chart(
            canvas, {
                "responsive": false,
                "type": "line",
                "data": {
                    "labels": ["January", "February", "March", "April", "May", "June", "July"],
                    "datasets": [{
                        "label": "My First Dataset",
                        "data": [65, 59, 80, 81, 56, 55, 40],
                        "fill": false,
                        "borderColor": "rgb(75, 192, 192)",
                        "lineTension": 0.1
                    }]
                },
                "options": {}
            }
        );
    }
    window.onload = function() {
        drawGraphs();
    };
    // function generateLimitData() {
    //     const data = [];
    //     const start = new Date('{{ $date->format('Y-m-d') }}T07:00:00');
    //     const end = new Date(start);
    //     end.setDate(end.getDate() + 1);
    //     end.setMinutes(end.getMinutes() - 1);

    //     for (var time = start; time <= end; time.setMinutes(time.getMinutes() + 5)) {
    //         const dayOfWeek = time.getDay();
    //         const isWeekend = (dayOfWeek === 0);
    //         const hours = time.getHours();
    //         var yValue;

    //         if (!isWeekend) {
    //             if (hours >= 7 && hours < 19) {
    //                 yValue = {{ $measurementPoint->soundLimit->mon_sat_7am_7pm_leq5min }};
    //             } else if (hours >= 19 && hours < 22) {
    //                 yValue = {{ $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq5min }};
    //             } else if (hours >= 22 && hours < 24) {
    //                 yValue = {{ $measurementPoint->soundLimit->mon_sat_10pm_12am_leq5min }};
    //             } else {
    //                 yValue = {{ $measurementPoint->soundLimit->mon_sat_12am_7am_leq5min }};
    //             }
    //         } else {
    //             if (hours >= 7 && hours < 19) {
    //                 yValue = {{ $measurementPoint->soundLimit->sun_ph_7am_7pm_leq5min }};
    //             } else if (hours >= 19 && hours < 22) {
    //                 yValue = {{ $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq5min }};
    //             } else if (hours >= 22 && hours < 24) {
    //                 yValue = {{ $measurementPoint->soundLimit->sun_ph_10pm_12am_leq5min }};
    //             } else {
    //                 yValue = {{ $measurementPoint->soundLimit->sun_ph_12am_7am_leq5min }};
    //             }
    //         }

    //         data.push({
    //             x: new Date(time).toISOString(),
    //             y: yValue
    //         });
    //     }

    //     return data;
    // }

    // function generateNoiseData() {
    //     const data = [];
    //     const start = new Date('{{ $date->format('Y-m-d') }}T07:00:00');
    //     const end = new Date(start);
    //     end.setDate(end.getDate() + 1);
    //     end.setMinutes(end.getMinutes() - 1);

    //     for (var time = start; time <= end; time.setMinutes(time.getMinutes() + 5)) {
    //         data.push({
    //             x: new Date(time).toISOString(),
    //             y: NaN
    //         });
    //     }

    //     @foreach ($noiseData as $item)
    //         var receiveAt = new Date('{{ $item->received_at }}');
    //         var receiveAtISO = receiveAt.toISOString();

    //         for (var i = 0; i < data.length; i++) {
    //             if (data[i].x === receiveAtISO) {
    //                 data[i].y = {{ $item->leq }};
    //                 break;
    //             }
    //         }
    //     @endforeach

    //     return data;
    // }

    // var dateStr = '{{ $date->format('d-m-Y') }}';
    // var canvas = document.getElementById('myChart' + dateStr);
    // var ctx = canvas.getContext('2d');
    // var img = document.getElementById('chartImage' + dateStr);

    // window.onload = function() {

    //     new Chart(
    //         document.getElementById("myChart"), {
    //             "responsive": false,
    //             "type": "line",
    //             "data": {
    //                 "labels": ["January", "February", "March", "April", "May", "June", "July"],
    //                 "datasets": [{
    //                     "label": "My First Dataset",
    //                     "data": [65, 59, 80, 81, 56, 55, 40],
    //                     "fill": false,
    //                     "borderColor": "rgb(75, 192, 192)",
    //                     "lineTension": 0.1
    //                 }]
    //             },
    //             "options": {}
    //         }
    //     );

    // var myChart = new Chart(ctx, {
    //     type: 'line',
    //     data: {
    //         datasets: [{
    //             label: 'Leq 5 Limit',
    //             data: generateLimitData(),
    //             borderColor: 'rgba(255, 99, 132, 1)',
    //             pointRadius: 0,
    //             borderWidth: 2,
    //             fill: false,
    //             stepped: true
    //         }, {
    //             label: 'Leq 5 Min',
    //             data: generateNoiseData(),
    //             borderColor: 'rgba(75, 192, 192, 1)',
    //             borderWidth: 2,
    //             pointRadius: 0,
    //             fill: false,
    //             spanGaps: true
    //         }],
    //     },
    //     options: {
    //         scales: {
    //             x: {
    //                 type: 'time',
    //                 time: {
    //                     unit: 'minute',
    //                     stepSize: 5,
    //                     displayFormats: {
    //                         minute: 'HH:mm'
    //                     }
    //                 },
    //                 ticks: {
    //                     autoSkip: true,
    //                     maxTicksLimit: 8
    //                 },
    //                 min: '{{ $date->format('Y-m-d') }}T07:00:00',
    //                 max: (new Date(new Date('{{ $date->format('Y-m-d') }}T07:00:00').getTime() + (
    //                     23 * 60 + 59) * 60 * 1000)).toISOString()
    //             },
    //             y: {
    //                 beginAtZero: true,
    //                 max: {{ $measurementPoint->soundLimit->get_max_leq5_limit($date) * 1.2 }}
    //             }
    //         },
    //         plugins: {
    //             legend: {
    //                 display: true
    //             }
    //         }
    //     }
    // });
    }
</script>
