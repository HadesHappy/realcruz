                <div class="row">
                    <div class="col-md-6">
                        <div class="content-group-sm">
                            <div class="d-flex">
                                <h5 class="text-semibold me-auto">{{ trans('messages.average_open_rate') }}</h5>
                                <div class="pull-right progress-right-info text-primary">{{ number_to_percentage($list->readCache('UniqOpenRate')) }}</div>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-striped bg-info" style="width: {{ number_to_percentage($list->readCache('UniqOpenRate')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="content-group-sm">
                            <div class="d-flex">
                                <h5 class="text-semibold me-auto">{{ trans('messages.average_click_rate') }}</h5>
                                <div class="pull-right progress-right-info text-primary">{{ number_to_percentage($list->readCache('ClickedRate')) }}</div>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-striped bg-info" style="width: {{ number_to_percentage($list->readCache('ClickedRate')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row mt-4 mb-4">
                    <div class="col-md-3">
                        <div class="bg-secondary p-3 shadow rounded-3 text-white">
                            <div class="text-center">
                                <h2 class="text-semibold mb-1 mt-0">{{ number_to_percentage($list->readCache('SubscribeRate')) }}</h2>
                                <div class="text-muted2 text-white">{{ trans('messages.avg_subscribe_rate') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-secondary p-3 shadow rounded-3 text-white">
                            <div class="text-center">
                                <h2 class="text-semibold mb-1 mt-0">{{ number_to_percentage($list->readCache('UnsubscribeRate')) }}</h2>
                                <div class="text-muted2 text-white">{{ trans('messages.avg_unsubscribe_rate') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-secondary p-3 shadow rounded-3 text-white">
                            <div class="text-center">
                                <h2 class="text-semibold mb-1 mt-0">{{ number_with_delimiter($list->readCache('UnsubscribeCount')) }}</h2>
                                <div class="text-muted2 text-white">{{ trans('messages.total_unsubscribers') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-secondary p-3 shadow rounded-3 text-white">
                            <div class="text-center">
                                <h2 class="text-semibold mb-1 mt-0">{{ number_with_delimiter($list->readCache('UnconfirmedCount')) }}</h2>
                                <div class="text-muted2 text-white">{{ trans('messages.total_unconfirmed') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
