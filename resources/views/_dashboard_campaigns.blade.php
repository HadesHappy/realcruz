<h3 class="mt-5 mb-3">
    <span class="material-symbols-rounded me-2">
        history_toggle_off
        </span>
    {{ trans('messages.recently_sent_campaigns') }}
</h3>

@if (Auth::user()->customer->sentCampaigns()->count() == 0)
    <div class="empty-list">
        <span class="material-symbols-rounded">
            auto_awesome
        </span>
        <span class="line-1">
            {{ trans('messages.no_sent_campaigns') }}
        </span>
    </div>
@else
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => 'dashboard-campaign-select',
                'name' => 'campaign_id',
                'label' => '',
                'value' => '',
                'options' => Acelle\Model\Campaign::getSelectOptions(Auth::user()->customer, "done"),
            ])
        </div>
    </div>
    <div id="campaign-quickview-container" data-url="{{ action("CampaignController@quickView") }}"></div>
@endif

<script>
    var DashboardCampaigns = {
        container: $('#campaign-quickview-container'),
        campaignSelect: $('.dashboard-campaign-select'),
        url: $('#campaign-quickview-container').attr('data-url'),

        getCampaignId: function() {
            return DashboardCampaigns.campaignSelect.val();
        },

        loadChart: function() {
            $.ajax({
                method: "GET",
                url: DashboardCampaigns.url,
                data: {
                    uid: DashboardCampaigns.getCampaignId()
                }
            })
            .done(function( response ) {
                DashboardCampaigns.container.html(response);
            });
        },
        init: function() {
            DashboardCampaigns.campaignSelect.on('change', function() {
                DashboardCampaigns.loadChart();
            });

            DashboardCampaigns.loadChart();
        }
    }

    DashboardCampaigns.init();
</script>