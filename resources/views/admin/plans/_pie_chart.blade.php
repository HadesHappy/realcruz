<div class="row">
    <div class="col-md-12">
        @if (\Auth::user()->admin->getAllSubscriptions()->count())
            <!-- Basic column chart -->
            <div class="border p-3 shadow-sm rounded-3">
                <div class="">
                    <div class="chart-container">
                        <div class="chart has-fixed-height-250"
                            id="PlansPieChart"
                            style="width: 100%;height:350px;"
                            data-url="{{ action('Admin\PlanController@pieChart') }}"
                        ></div>
                    </div>
                </div>
            </div>
            <!-- /basic column chart -->
        @else
            <div class="empty-chart-pie">
                <div class="empty-list has-fixed-height-300">
                    <span class="material-symbols-rounded">
auto_awesome
</span>
                    <span class="line-1">
                        {{ trans('messages.log_empty_line_1') }}
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    $(function() {
        PlansPieChart.showChart();
    });
    var PlansPieChart = {
        url: '{{ action('Admin\PlanController@pieChart') }}',
        getChart: function() {
            return $('#PlansPieChart');
        },

        showChart: function() {
            $.ajax({
                method: "GET",
                url: this.url,
            })
            .done(function( response ) {
                PlansPieChart.renderChart( response.data );
            });
        },

        renderChart: function(data) {
            // based on prepared DOM, initialize echarts instance
            var growthChart2 = echarts.init(PlansPieChart.getChart()[0], ECHARTS_THEME);

            var colors = [
                '#5cb2b2',
                '#b25977',
                '#aab25a',
                '#5b7bb2',
                '#555555',
                '#626eb2',
                '#81ac8d',
                '#7d5fb2',
                '#b26e59'
            ];

            var cData = data.map(function(item, index) {
                return {
                    name: item.name,
                    value: item.value,
                    itemStyle: {
                        color: colors[index],
                        borderWidth: 1,  borderType: 'solid', borderColor: '#fff'
                    }
                };
            });

            var option = {
                title: {
                    text: '{{ trans('messages.plans') }}',
                    // subtext: '{{ trans('messages.statistics_chart') }}',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}: {c} ({d}%)'
                },
                legend: {
                    orient: 'vertical',
                    left: 'right',
                },
                series: [
                    {
                        selectedMode: 'single',
                        type: 'pie',
                        radius: '70%',
                        data: cData,
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        label: {
                            // position: 'inner',
                            fontSize: 12,
                            color: ECHARTS_THEME == 'dark' ? '#fff' : null,
                            formatter: '{b}\n{d}% ({c})',
                        },
                    }
                ]
            };

            // use configuration item and data specified to show chart
            growthChart2.setOption(option);
        }
    }    
</script>
