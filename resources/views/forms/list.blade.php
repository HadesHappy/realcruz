@if ($forms->count() > 0)
	<table class="table table-box pml-table mt-2">
		@foreach ($forms as $key => $form)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $form->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td width="1%">
                    <a href="{{ action('FormController@frontendContent', $form->uid) }}"
                        data-overlay="{{ $form->getMetadata('overlay_opacity') ? ($form->getMetadata('overlay_opacity')/100) : '0.2' }}"
                        class="form-popup-preview">
                        <img class="form-thumb rounded shadow-sm border ms-1" width="100"
                            src="{{ $form->template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                    </a>
                </td>
                <td>
                    <a class="kq_search fs-6 d-block mb-1 fw-600" href="{{ action('FormController@build', [
                        'uid' => $form->uid,
                    ]) }}">
                        {{ $form->name }}
                    </a>
                    <span title="{{ $form->mailList->name }}" class="xtooltip text-muted2 text-truncate d-block"
                        style="max-width: 220px">
                        {{ $form->mailList->name }}
                    </span>
                </td>

                <td class="pe-5">
                    @if ($form->getWebsite())
                        <a title="{{ $form->mailList->name }}" href="{{ action('WebsiteController@index', [
                            'uid' => $form->getWebsite()->uid
                        ]) }}" class="no-margin stat-num d-block text-truncate xtooltip" style="max-width: 220px">
                            <span>{{ $form->getWebsite()->title }}</span>
                        </a>
                    @else
                        <div><span>--</span></div>
                    @endif
                        
                    <span class="text-muted2">{{ trans('messages.form.site') }}</span>
                </td>

                <td>
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $form->status }}">{{ trans('messages.form.status.' . $form->status) }}</span>
                    </span>
                </td>

                <td class="text-end text-nowrap pe-0">
                    @if (Auth::user()->customer->can('update', $form))
                        <a href="{{ action('FormController@build', $form->uid) }}"
                                role="button" class="btn btn-primary">
                            <span class="material-symbols-rounded me-1">handyman</span> {{ trans('messages.form.builder') }}
                        </a>
                    @endif

                    @if (Auth::user()->customer->can('publish', $form))
                        <a link-method="POST" href="{{ action('FormController@publish', [
                            'uids' => [$form->uid],
                        ]) }}"
                            role="button" class="btn btn-secondary list-action-single">
                            <span class="material-symbols-rounded me-1">
                                task_alt
                                </span> {{ trans('messages.form.publish') }}
                        </a>
                    @endif

                    @if (Auth::user()->customer->can('unpublish', $form))
                        <a link-method="POST" href="{{ action('FormController@unpublish', [
                            'uids' => [$form->uid],
                        ]) }}"
                            role="button" class="btn btn-default list-action-single">
                            <span class="material-symbols-rounded me-1">
                                do_disturb_on
                                </span> {{ trans('messages.form.unpublish') }}
                        </a>
                    @endif
                    
                    <a href="{{ action('FormController@frontendContent', $form->uid) }}"
                        data-overlay="{{ $form->getMetadata('overlay_opacity') ? ($form->getMetadata('overlay_opacity')/100) : '0.2' }}"
                        role="button" class="btn btn-default form-popup-preview">
                        <span class="material-symbols-rounded">
                            zoom_in
                            </span>
                    </a>

                    @if (
                        Auth::user()->customer->can('update', $form)
                    )
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if ($form->isPublished() && $form->getWebsite())
                                    <li><a target="_blank" href="{{ $form->getWebsite()->url }}"
                                            class="dropdown-item"
                                        >
                                        <span class="material-symbols-rounded me-2" style="font-size:11px">
                                            launch
                                            </span>
                                    {{ trans('messages.form.view_on_site') }}
                                        
                                    </a></li>
                                @endif
                                @if (Auth::user()->customer->can('delete', $form))
                                    <li><a
                                        class="dropdown-item list-action-single"
                                        link-method="POST"
                                        link-confirm="{{ trans('messages.forms.delete.confirm') }}"
                                        href="{{ action('FormController@delete', ["uids" => $form->uid]) }}">
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
	@include('elements/_per_page_select', ["items" => $forms])

    <script>
        $(function() {
            $('.form-popup-preview').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                var overlay = $(this).attr('data-overlay');

                popup = new AFormPopup({
                    url: url,
                    overlayOpacity: overlay
                });

                popup.load();
            });
        });
    </script>
@elseif (!empty(request()->keyword) || !empty(request()->mail_list_uid))
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
