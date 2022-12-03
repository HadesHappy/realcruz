<h4 class="mt-4 mb-3">{{ trans('messages.statistics') }}</h4>
<p class="mb-3">{!! trans('messages.campaign_table_chart_intro') !!}</p>

<div class="row">
    <div class="col-md-6">
        <div class="">
            <div class="">
                <div class="chart-container">
                    <div id="campaignChart"
                        class="border shadow-sm rounded"
                        data-url="{{ action('CampaignController@chart', $campaign->uid) }}"
                        style="width:100%; height:350px;"
                    ></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="badge-row">
            <span class="badge bg-secondaryx bg-color0 badge-big me-2">{{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}</span>
            {{ trans('messages.opened') }}
            <span class="fw-600">
                {{ trans('messages.open_uniq_per_total', [
                    'count' => $campaign->readCache('UniqOpenCount'),
                    'total' => $campaign->uniqueOpenCount(),
                    'delivered' => $campaign->deliveredCount(),
                ]) }}
            </span><span class="px-2"> · </span>
            <a class="text-warning" href="{{ action('CampaignController@openLog', $campaign->uid) }}"><span class="material-symbols-rounded">
arrow_forward
</span> {{ trans('messages.view_log') }}</a>
        </div>

        <div class="badge-row">
            <span class="badge bg-secondaryx bg-color1 badge-big me-2">{{ number_to_percentage($campaign->readCache('NotOpenRate', 0)) }}</span>
            {{ trans('messages.not_opened') }}
            <span class="fw-600">
                {{ trans('messages.not_open_per_total', [
                    'count' => $campaign->readCache('NotOpenCount', 0),
                    'total' => $campaign->readCache('SubscriberCount', 0),
                ]) }}
            </span><span class="px-2"> · </span>
            <a class="text-warning" href="{{ action('CampaignController@subscribers', ['uid' => $campaign->uid, 'open' => 'not_opened']) }}"><span class="material-symbols-rounded">
arrow_forward
</span> {{ trans('messages.view_log') }}</a>
        </div>

        <div class="badge-row">
            <span class="badge bg-secondaryx bg-color2 badge-big me-2">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
            {{ trans('messages.clicked_emails_rate') }}
            <span class="fw-600">
                {{ trans('messages.count_clicked_opened', [
                    'count' => $campaign->uniqueClickCount(),
                    'total' => $campaign->openUniqCount()
                ]) }}
            </span><span class="px-2"> · </span>
            <a class="text-warning" href="{{ action('CampaignController@clickLog', $campaign->uid) }}"><span class="material-symbols-rounded">
arrow_forward
</span> {{ trans('messages.view_log') }}</a>
        </div>

        <div class="badge-row">
            <span class="badge bg-secondaryx bg-color3 badge-big me-2">{{ number_to_percentage($campaign->unsubscribeRate()) }}</span>
            {{ trans('messages.unsubscribed') }}
            <span class="fw-600">
                {{ trans('messages.count_unsubscribed', [
                    'count' => $campaign->unsubscribeCount()
                ]) }}
            </span><span class="px-2"> · </span>
            <a class="text-warning" href="{{ action('CampaignController@unsubscribeLog', $campaign->uid) }}"><span class="material-symbols-rounded">
arrow_forward
</span> {{ trans('messages.view_log') }}</a>
        </div>

        <div class="badge-row">
            <span class="badge bg-secondaryx bg-color4 badge-big me-2">{{ number_to_percentage($campaign->bounceRate()) }}</span>
            {{ trans('messages.bounced') }}
            <span class="fw-600">
                {{ trans('messages.count_bounced', [
                    'count' => $campaign->bounceCount()
                ]) }}
            </span><span class="px-2"> · </span>
            <a class="text-warning" href="{{ action('CampaignController@bounceLog', $campaign->uid) }}"><span class="material-symbols-rounded">
arrow_forward
</span> {{ trans('messages.view_log') }}</a>
        </div>

        <div class="badge-row">
            <span class="badge bg-secondaryx bg-color5 badge-big me-2">{{ number_to_percentage($campaign->feedbackRate()) }}</span>
            {{ trans('messages.reported') }}
            <span class="fw-600">
                {{ trans('messages.count_reported', [
                    'count' => $campaign->feedbackCount()
                ]) }}
            </span><span class="px-2"> · </span>
            <a class="text-warning" href="{{ action('CampaignController@feedbackLog', $campaign->uid) }}"><span class="material-symbols-rounded">
arrow_forward
</span> {{ trans('messages.view_log') }}</a>
        </div>

    </div>
</div>


<script>
    var CampaignsChart = {
        chart: $('#campaignChart'),
        url: $('#campaignChart').attr('data-url'),

        init: function() {
            CampaignsChart.showChart();
        },

        showChart: function() {
            $.ajax({
                method: "GET",
                url: CampaignsChart.url,
            })
            .done(function( response ) {
                CampaignsChart.renderChart( response );
            });
        },

        renderChart: function(data) {
                // based on prepared DOM, initialize echarts instance
                var myChart = echarts.init(CampaignsChart.chart[0], ECHARTS_THEME);
                var colors = [
                    '#555555',
                    '#626eb2',
                    '#81ac8d',
                    '#7d5fb2',
                    '#b26e59',
                    '#5cb2b2',
                    '#b25977',
                    '#aab25a',
                    '#5b7bb2',
                ];
                
                // mapping cols
                var cols = data.map(function(item) {
                    return item.name;
                }).reverse();

                // mapping data
                var cData = data.map(function(item, index) {
                    return {
                        name: item.name,
                        value: item.value,
                        itemStyle: {
                            color: colors[index]
                        }
                    };
                }).reverse();

                var option = {
                    grid: {
                        left: '100px',
                        right: '100px',
                        top: '30px',
                        bottom: '50px'
                    },
                    yAxis: {
                        type: 'category',
                        data: cols,
                    },
                    xAxis: {
                        type: 'value',
                        name: '{{ trans('messages.subscribers') }}',
                        scale: true
                    },
                    series: [{
                        data: cData,
                        type: 'bar'
                    }]
                    // tooltip: {
                    //     trigger: 'axis',
                    //     axisPointer: {
                    //         type: 'shadow'
                    //     }
                    // },
                    // grid: {
                    //     left: '3%',
                    //     right: '4%',
                    //     bottom: '3%',
                    //     containLabel: true
                    // },
                    // xAxis: {
                    //     type: 'value',
                    //     boundaryGap: [0, 0.01]
                    // },
                    // yAxis: {
                    //     type: 'value'
                    // },
                    // series: [
                    //     {
                    //         type: 'bar',
                    //         data: {
                    //             name: 'Unsubscribe',
                    //             value: 10000 
                    //         }
                    //     }
                    // ]
                };

                // // specify chart configuration item and data
                // var option = {
                //     legend: {
                //         right: 0,
                //         top: 5,
                //         orient: 'vertical',
                //         icon: 'circle',
                //     },
                //     tooltip: {
                //         trigger: 'item',
                //         formatter: '{a} <br/>{b} : {c} ({d}%)'
                //     },
                //     toolbox: {
                //         show: false,
                //         feature: {
                //             mark: {show: true},
                //             dataView: {show: true, readOnly: false},
                //             restore: {show: true},
                //             saveAsImage: {show: true}
                //         }
                //     },
                //     series: [
                //         {
                //             name: 'Activities',
                //             type: 'bar',
                //             center: ['45%', '40%'],
                //             selectedMode: 'single',
                //             itemStyle: {
                //                 borderRadius: 0
                //             },
                //             label: {
                //                 position: 'inner',
                //                 fontSize: 14,
                //                 formatter: '{d}%',
                //             },
                //             data: [
                //                 {value: 45, name: 'Work', itemStyle: { color: '#6a7796', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' } },
                //                 {value: 27, name: 'Eat', itemStyle: { color: '#906659', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }},
                //                 {value: 11, name: 'Commute', itemStyle: { color: '#a5895d', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }},
                //                 {value: 22, name: 'Watch TV', itemStyle: { color: '#476844', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }},
                //                 {value: 28, name: 'Sleep', itemStyle: { color: '#5f3763', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }}
                //             ],
                //         }
                //     ]
                // };

                // use configuration item and data specified to show chart
                myChart.setOption(option);
        }
    }
    CampaignsChart.init();
</script>