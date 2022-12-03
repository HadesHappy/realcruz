<!--
<div class="subsection mt-5 pt-5">
    <div class="row">
        <div class="col-md-6">
            <div id="main" style="width:550px; height:500px;margin-bottom:-50px"></div>

            <script type="text/javascript">
                // based on prepared DOM, initialize echarts instance
                var myChart = echarts.init(document.getElementById('main'), ECHARTS_THEME);

                // specify chart configuration item and data
                option = {
                    legend: {
                        right: 0,
                        top: 5,
                        orient: 'vertical',
                        icon: 'circle',
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    toolbox: {
                        show: false
                    },
                    series: [
                        {
                            name: 'Activities',
                            type: 'pie',
                            center: ['45%', '40%'],
                            selectedMode: 'single',
                            itemStyle: {
                                borderRadius: 0
                            },
                            label: {
                                position: 'inner',
                                fontSize: 14,
                                formatter: '{d}%',
                            },
                            data: [
                                {value: 45, name: 'Work', itemStyle: { color: '#6a7796', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' } },
                                {value: 27, name: 'Eat', itemStyle: { color: '#906659', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }},
                                {value: 11, name: 'Commute', itemStyle: { color: '#a5895d', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }},
                                {value: 22, name: 'Watch TV', itemStyle: { color: '#476844', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }},
                                {value: 28, name: 'Sleep', itemStyle: { color: '#5f3763', borderWidth: 1,  borderType: 'solid', borderColor: '#fff' }}
                            ],
                        }
                    ]
                };

                // use configuration item and data specified to show chart
                myChart.setOption(option);
            </script>
        </div>
        <div class="col-md-5">
            <p class="d-flex align-items-center mb-2">
                <span class="d-flex mr-3" title='{{ $campaign->status == Acelle\Model\Campaign::STATUS_ERROR ? $campaign->last_error : '' }}' data-popup='tooltip'>
                    <span class="label label-flat bg-{{ $campaign->status }}">{{ trans('messages.campaign_status_' . $campaign->status) }}</span>
                </span>
                <span class="text-semibold">
                    {{ trans('messages.campaign.started_ago', [
                        'time' => $campaign->run_at ? $campaign->run_at->diffForHumans() : '',
                    ]) }} <strong> · </strong> {{ Auth::user()->customer->formatDateTime($campaign->run_at, 'date_full') }}
                </span>
            </p>
            <h2 class="mt-4 mb-2">100% delivered to active contacts</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris in tortor tortor. Quisque pulvinar, turpis quis ultrices vestibulum, massa elit facilisis dui, a malesuada felis quam in dolor. Suspendisse nec tristique quam, ut hendrerit eros.</p>

            <a href="{{ action('CampaignController@resend', ["uid" => $campaign->uid]) }}" class="btn btn-secondary resend-campaign">{{ trans("messages.campaign.resend") }}</a>
        </div>
    </div>
</div>
-->