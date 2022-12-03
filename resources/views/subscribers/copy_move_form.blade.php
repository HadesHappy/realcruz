@extends('layouts.popup.small')

@section('content')
<!-- Basic modal -->
<div id="copy-move-subscribers-form" class="">
            <form action="{{ action("SubscriberController@" . request()->action) }}" method="POST" class="form-validate-jquery">
                {{ csrf_field() }}
                <input type="hidden" name="from_uid" value="{{ $from_list->uid }}" />
                @foreach (request()->all() as $name => $value)
                    @if (is_array($value))
                        @foreach ($value as $v)
                            <input type="hidden" name="{{ $name }}[]" value="{{ $v }}" />
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
                    @endif
                @endforeach


                    <h2 class="mb-3">
                        {{ trans('messages.' . request()->action . '_subscriber') }}
                    </h2>
                    <p>{!! trans('messages.subscribers_' . request()->action . '_message', ['number' => $subscribers->count()]) !!}</p>

                    <input type="hidden" name="action" value="{{ request()->action }}" />
                    <input type="hidden" name="uids" value="{{ request()->uids }}" />
                    <?php
                        $lists = collect(Auth::user()->customer->readCache('MailListSelectOptions', []));
                        $lists = $lists->filter(function ($record, $key) use ($from_list) {
                            return $record['id'] != $from_list->id;
                        });
                    ?>
                    @include('helpers.form_control', [
                        'type' => 'select',
                        'name' => 'to_uid',
                        'class' => 'required',
                        'required' => true,
                        'label' => trans('messages.select_the_target_list'),
                        'value' => '',
                        'include_blank' => trans('messages.choose'),
                        'options' => $lists,
                        'rules' => []
                    ])

                    @include('helpers.form_control', [
                        'type' => 'radio',
                        'name' => 'type',
                        'class' => '',
                        'label' => trans('messages.action_when_email_exist'),
                        'value' => 'update',
                        'options' => Acelle\Model\Subscriber::copyMoveExistSelectOptions(),
                        'rules' => []
                    ])

                <div class="">
                    <button type="submit" class="btn btn-primary bg-teal">{{ trans('messages.submit') }}</button>
                    <button role="button" class="btn btn-white" onclick="copyMovePopup.hide()">{{ trans('messages.cancel') }}</button>
                </div>
            </form>
</div>
<!-- /basic modal -->
@endsection
