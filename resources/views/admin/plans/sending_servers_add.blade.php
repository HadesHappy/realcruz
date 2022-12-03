@extends('layouts.popup.small')

@section('title')
  {{ trans('messages.plan.sending_servers.add') }}
@endsection

@section('content')

  <form enctype="multipart/form-data" action="{{ action('Admin\PlanController@addSendingServer', $plan->uid) }}" method="POST" class="form-validate-jquery">
      {{ csrf_field() }}
      @if ($noSendingServer)
          <div class="">
            @include('helpers.form_control', [
                'type' => 'select_ajax',
                'name' => 'sending_server_uid',
                'class' => 'hook required',
                'label' => trans('messages.plan.sending_servers.select'),
                'selected' => [
                  'value' => '',
                  'text' => '',
                ],
                'help_class' => 'subscription',
                'rules' => [],
                'url' => action('Admin\SendingServerController@select2', ['plan_uid' => $plan->uid]),
                'placeholder' => trans('messages.plan.sending_servers.choose')
            ])
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-secondary">{{ trans('messages.plan.sending_servers.add.ok') }}</button>
            <button role="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.close') }}</button>
          </div>
      @else
          <div class="">
              @include('elements._notification', [
                  'level' => 'warning',
                  'message' => trans('messages.plan.sending_servers.add_empty_warning', ['link' => action('Admin\SendingServerController@index')])
              ])
          </div>
      @endif
  <form>
@endsection
