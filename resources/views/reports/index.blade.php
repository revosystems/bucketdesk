@extends('layout')
@section('content')
    <div class="panel fullWidth">
        <div style="height:200px; max-width: 99%">
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
                        'green',
                    ],
                    borderWidth: 1
                },{
                    label: 'Fixed',
                    data: @json($fixed),
                    backgroundColor: [
                        'red',
                    ],
                    borderColor: [
                        'transparent',
                    ],
                    borderWidth: 1
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
                        radius:0
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