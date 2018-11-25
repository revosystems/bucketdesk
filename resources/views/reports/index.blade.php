@extends('layout')
@section('content')
    <div class="mt4">
        <div style="height:300px">
            <canvas id="myChart" width="1000"></canvas>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" text="javascript"></script>
    <script>
            <?php
            $labels         = $report->keys();
            $new            = $report->pluck('new');
            $fixed          = $report->pluck('fixed')
            ?>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'New',
                    data: @json($new),
                    backgroundColor: [
                        '#F6FAFD',
                    ],
                    borderColor: [
                        '#66A1DB',
                    ],
                    borderWidth: 2
                },{
                    label: 'Fixed',
                    data: @json($fixed),
                    backgroundColor: [
                        '#EEEEEE',
                    ],
                    borderColor: [
                        '#AAAAAA',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive:true,
                maintainAspectRatio: false,
                elements: {
                    line: {
                        tension: 0, // disables bezier curves
                    },
                    point: {
                        radius:2
                    }
                },
                scales: {
                    xAxes: [ { gridLines: { display:false } } ],
                    yAxes: [{
                        gridLines: { display:false },
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script>
@stop