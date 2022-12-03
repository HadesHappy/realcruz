<h3 class="mt-5 mb-20"><span class="material-symbols-rounded me-2">
    schedule
    </span> {{ trans('messages.24h_performance') }}</h3>
<p class="mb-4">{{ trans('messages.campaign_24h_intro') }}</p>
<div class="border shadow-sm rounded">
    <div class="p-3">
        <div id="Campaigns24hChart"
            class=""
            style="width:100%; height:350px;"
        ></div>
    </div>
</div>

<script>
    var Campaigns24hChart = {
        url: '{{ action('CampaignController@chart24h', $campaign->uid) }}',
        getChart: function() {
            return $('#Campaigns24hChart');
        },

        showChart: function() {
            $.ajax({
                method: "GET",
                url: this.url,
            })
            .done(function( response ) {
                Campaigns24hChart.renderChart( response );
            });
        },

        renderChart: function(data) {
                // based on prepared DOM, initialize echarts instance
                var my2Chart = echarts.init(Campaigns24hChart.getChart()[0], ECHARTS_THEME);

                var option = {
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['{{ trans('messages.opened') }}', '{{ trans('messages.clicked') }}']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        show: false
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: data.columns
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                            name: '{{ trans('messages.opened') }}',
                            type: 'line',
                            itemStyle: {
                                color: '#5cb2b2'
                            },
                            data: data.opened
                        },
                        {
                            name: '{{ trans('messages.clicked') }}',
                            type: 'line',
                            itemStyle: {
                                color: '#b26e59'
                            },
                            data: data.clicked
                        }
                    ]
                };

                // use configuration item and data specified to show chart
                my2Chart.setOption(option);
        }
    }

    Campaigns24hChart.showChart();
</script>