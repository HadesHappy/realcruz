@extends('layouts.popup.medium')

@section('content')  
    <div class="d-flex">
        <div class="pr-4">
            <h4 class="mt-0">Little Kitty Store</h4>
            <p>Overview of your campaign performance
                <br>Click on a number to see its details</p>
        </div>
        <div class="ml-auto pl-4">
            <button class="btn btn-secondary" onclick="timelinePopup.load()">Refresh</button>
        </div>
    </div>  
           

    <div class="">
        <div class="email-row d-flex align-items-center">
            <div class="mr-3 d-flex align-items-center">
                <span class="material-symbols-rounded">
                    pause_circle
                </span>
            </div>
            <div class="content">
                <div class="mb-1">Email title: <strong class="font-weight-semibold">This is an email Title</strong></div>
                <div class="small text-muted">
                    Sent <strong class="font-weight-semibold">12 hours</strong> after items are left in cart
                </div>
            </div>
            <div class="stats ml-auto d-flex">
                <div class="prate">
                    <div class="percent">0.0%</div>
                    <div class="text-muted small">Opens</div>
                </div>
                <div class="prate ml-4 pl-2">
                    <div class="percent">0.0%</div>
                    <div class="text-muted small">Click</div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-boxes mt-4 d-flex justify-content-space-between">
        <div class="tab-box bg-yellow-light" onclick="timelinePopup.load('{!!
                action('Automation2Controller@cartList', $automation->uid)
            !!}')">
            <div class="number mb-2">
                14 / $120
            </div>
            <div class="desc small">
                items/total value currently in carts, from 3 buyer
            </div>
        </div>
        <div class="tab-box" onclick="timelinePopup.load('{!!
            action('Automation2Controller@cartItems', $automation->uid)
        !!}')">
            <div class="number mb-2">
                75
            </div>
            <div class="desc small">
                Notification emails sent
                during the last 3 days
            </div>
        </div>
        <div class="tab-box" onclick="timelinePopup.load('{!!
            action('Automation2Controller@cartItems', $automation->uid)
        !!}')">
            <div class="number mb-2">
                $1,200
            </div>
            <div class="desc small">
                Converted revenue
                from campaign
            </div>
        </div>
    </div>

    <div class="mt-4 pt-3">
        <h5 class="font-weight-semibold">Monthly performance</h5>
        <canvas id="myChart" width="620" height="500"></canvas>
        <script>
            $(function() {
                myChart.showChart();
            });
            var myChart = {
                url: '',
                getChart: function() {
                    return $('#myChart');
                },
        
                showChart: function() {
                    $.ajax({
                        method: "GET",
                        url: this.url,
                    })
                    .done(function( response ) {
                        myChart.renderChart({});
                    });
                },
        
                renderChart: function(data) {
                    // based on prepared DOM, initialize echarts instance
                    var chart = echarts.init(myChart.getChart()[0]);

                    var option = {
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [{
                            data: [820, 932, 901, 934, 1290, 1330, 1320],
                            type: 'line',
                            areaStyle: {}
                        }]
                    };
        
                    // use configuration item and data specified to show chart
                    chart.setOption(option);
                }
            }    
        </script>
    </div>
@endsection