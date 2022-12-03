@if ($websites->count() > 0)
	<table class="table table-box pml-table mt-2">
		@foreach ($websites as $key => $website)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $website->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td class="">
                    <a class="kq_search fs-6 d-block mb-1 fw-600" href="{{ action('WebsiteController@show', [
                        'uid' => $website->uid,
                    ]) }}">
                        {{ $website->title }}
                    </a>

                    <span class="text-muted2">
                        {{ $website->url }}
                    </span>
                </td>

                <td class="pe-5">
                    <a href="{{ action('FormController@index') }}" class="no-margin stat-num d-block">
                        <span>{{ $website->connectedForms()->count() }}</span>
                    </a>
                    <span class="text-muted2">{{ trans('messages.website.connected_form') }}</span>
                </td>

                <td>
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $website->status }}">{{ trans('messages.website.status.' . $website->status) }}</span>
                    </span>
                </td>

                <td class="text-end">
                    <a href="{{ action('WebsiteController@show', $website->uid) }}"
                        role="button" class="btn btn-primary form-popup-preview">
                        <span class="material-symbols-rounded me-1">
                            code
                            </span> {{ trans('messages.website.view_code') }}
                    </a>

                    @if (Auth::user()->customer->can('disconnect', $website))
                        <a href="{{ action('WebsiteController@disconnect', [
                            'uids' => [$website->uid],
                        ]) }}"
                            link-method="POST"
                            role="button" class="btn btn-secondary list-action-single">
                            <span class="material-symbols-rounded me-1">
                                pause_circle
                                </span> {{ trans('messages.website.disconnect') }}
                        </a>
                    @endif
                    
                    @if (Auth::user()->customer->can('connect', $website))
                        <a href="{{ action('WebsiteController@connect', $website->uid) }}"
                            role="button" class="btn btn-secondary connect-site">
                            <span class="material-symbols-rounded me-1">
                                pause_circle
                                </span> {{ trans('messages.website.check_connection') }}
                        </a>
                    @endif

                    @if (
                        Auth::user()->customer->can('update', $website)
                    )
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()->customer->can('delete', $website))
                                    <li><a
                                        class="dropdown-item list-action-single"
                                        link-method="POST"
                                        link-confirm="{{ trans('messages.websites.delete.confirm') }}"
                                        href="{{ action('WebsiteController@delete', ["uids" => $website->uid]) }}">
                                        <span class="material-symbols-rounded me-2">
delete_outline
</span> {{ trans("messages.delete") }}</a></li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $websites])

    <script>
        var WebsitesList = {
            check: function(url) {
                addMaskLoading();
                $.ajax({
                    url : url,
                    type: "GET",
                    globalError: false
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    new Dialog('alert', {
                        title: '{{ trans('messages.notify.error') }}',
                        message: JSON.parse(jqXHR.responseText).error
                    });

                    removeMaskLoading();
                }).done(function(res) {
                    new Dialog('alert', {
                        title: '{{ trans('messages.notify.success') }}',
                        message: res.message
                    });

                    removeMaskLoading();
                });
            },

            connect: function(url) {
                addMaskLoading();
                $.ajax({
                    url : url,
                    type: "POST",
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    globalError: false
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    new Dialog('alert', {
                        title: '{{ trans('messages.notify.error') }}',
                        message: JSON.parse(jqXHR.responseText).error
                    });

                    removeMaskLoading();
                }).done(function(res) {
                    new notify({
                        message: res.message
                    });

                    removeMaskLoading();

                    WebsitesIndex.getList().load();
                });
            }
        }
        $(function() {
            $('.check-site-connect').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                WebsitesList.check(url);
            });

            $('.connect-site').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                WebsitesList.connect(url);
            });
        });
    </script>
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-symbols-rounded">
			auto_awesome
			</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-symbols-rounded">
			auto_awesome
			</span>
		<span class="line-1">
			{{ trans('messages.form.empty_list') }}
		</span>
	</div>
@endif
