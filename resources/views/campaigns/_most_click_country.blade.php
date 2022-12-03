<h3 class="mt-5 mb-3"><span class="material-symbols-rounded me-2">
    ads_click
    </span> {{ trans('messages.top_country_by_clicks') }}</h3>
<div class="row">
    <div class="col-md-6">
        @if (!$campaign->clickCount())                        
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
        @else
            <div class="stat-table">
                @foreach ($campaign->topClickCountries(7)->get() as $location)
                    <div class="stat-row">
                        <p class="text-muted">{{ $location->country_name }}</p>
                        <span class="pull-right num">
                            {{ number_with_delimiter($location->aggregate, $precision = 0) }}
                        </span>
                    </div>
                @endforeach 
            </div>
            <div class="text-end">
                <a href="{{ action('CampaignController@clickLog', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.click_log') }} <span class="material-symbols-rounded">
arrow_forward
</span></a>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        @if ($campaign->clickCount())
            <div class="border p-3 shadow-sm rounded">
                <div class="">
                    <div class="chart-container has-scroll">
                        <div class="chart has-fixed-height"
                            id="ClickPieChart" 
                            style="width:100%; height:350px;"
                            data-url="{{ action('CampaignController@chartClickCountry', $campaign->uid) }}">
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(function() {
                    ClickPieChart.showChart();
                });
                var ClickPieChart = {
                    url: '{{ action('CampaignController@chartClickCountry', $campaign->uid) }}',
                    getChart: function() {
                        return $('#ClickPieChart');
                    },
            
                    showChart: function() {
                        $.ajax({
                            method: "GET",
                            url: this.url,
                        })
                        .done(function( response ) {
                            ClickPieChart.renderChart( response.data );
                        });
                    },
            
                    renderChart: function(data) {
                        // based on prepared DOM, initialize echarts instance
                        var growthChart2 = echarts.init(ClickPieChart.getChart()[0], ECHARTS_THEME);

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
                                text: '{{ trans('messages.countries') }}',
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