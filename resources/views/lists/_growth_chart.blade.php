<div class="row">
    <div class="col-md-6">
        <!-- Basic column chart -->
        <div class="border p-3 shadow-sm rounded">
            <div class="">
                <div class="chart-container">
                    <div id="ListsGrowthChart1"
                        class="chart has-fixed-height"
                        style="width: 100%;height:450px;"
                    ></div>
                </div>
            </div>
        </div>
        <!-- /basic column chart -->

        <script>
            var ListsGrowthChart1 = {
                url: '{{ action('MailListController@listGrowthChart', $list->uid) }}',
                getChart: function() {
                    return $('#ListsGrowthChart1');
                },
        
                showChart: function() {
                    $.ajax({
                        method: "GET",
                        url: this.url,
                    })
                    .done(function( response ) {
                        ListsGrowthChart1.renderChart( response );
                    });
                },
        
                renderChart: function(data) {
                        // based on prepared DOM, initialize echarts instance
                        var growthChart1 = echarts.init(ListsGrowthChart1.getChart()[0], ECHARTS_THEME);
        
                        var option = {
                            title: {
                                text: '{{ trans('messages.subscribers') }}',
                                subtext: '{{ trans('messages.growth_chart') }}',
                                left: 'center'
                            },
                            xAxis: {
                                type: 'category',
                                data: data.columns
                            },
                            yAxis: {
                                type: 'value',
                                name: '{{ trans('messages.subscribers') }}',
                            },
                            series: [{
                                label: {
                                    show: true
                                },
                                itemStyle: {
                                    color: '#5cb2b2'
                                },
                                data: data.data,
                                type: 'bar'
                            }]
                        };
        
                        // use configuration item and data specified to show chart
                        growthChart1.setOption(option);
                }
            }
        
            ListsGrowthChart1.showChart();
        </script>
    </div>
    <div class="col-md-6">
        @if ($list->readCache('SubscriberCount') || (!isset($list->id) && Auth::user()->customer->readCache('SubscriberCount')))
            <!-- Basic column chart -->
            <div class="border p-3 shadow-sm rounded">
                <div class="">
                    <div class="chart-container">
                        <div id="ListsGrowthChart2"
                            class="chart has-fixed-height"
                            style="width: 100%;height:450px;"
                        ></div>
                    </div>
                </div>
            </div>
            <!-- /basic column chart -->

            <script>
                var ListsGrowthChart2 = {
                    url: '{{ action('MailListController@statisticsChart', $list->uid) }}',
                    getChart: function() {
                        return $('#ListsGrowthChart2');
                    },
            
                    showChart: function() {
                        $.ajax({
                            method: "GET",
                            url: this.url,
                        })
                        .done(function( response ) {
                            ListsGrowthChart2.renderChart( response );
                        });
                    },
            
                    renderChart: function(data) {
                            // based on prepared DOM, initialize echarts instance
                            var growthChart2 = echarts.init(ListsGrowthChart2.getChart()[0], ECHARTS_THEME);

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

                            var cData = data.data.map(function(item, index) {
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
                                    text: '{{ trans('messages.subscribers') }}',
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
            
                ListsGrowthChart2.showChart();
            </script>
        @else
            <div class="empty-chart-pie">
                <div class="empty-list">
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
