@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h3 class="mb-3">{{ trans('messages.automation.add_an_action') }}</h3>
            <p>{{ trans('messages.automation.action.intro') }}</p>
                
            <div class="line-list">
                @foreach ($types as $type)
                    @php
                        $disabled = ($type == 'condition' && $hasChildren == "true") ? 'd-disabled' : '';
                    @endphp
                    <div class="d-flex align-items-center line-item action-select-but action-select-{{ $type }} {{ $disabled }}" data-key="{{ $type }}">
                        <div class="line-icon">
                            @if ($type == 'send-an-email')
                                <img width="30px" class="icon-img d-inline-block" src="{{ url('images/icons/email-right.svg') }}" />
                            @elseif ($type == 'wait')
                                <img width="30px" class="icon-img d-inline-block" src="{{ url('images/icons/wait.svg') }}" />
                            @elseif ($type == 'condition')
                                <img width="30px" class="icon-img d-inline-block" src="{{ url('images/icons/condition.svg') }}" />
                            @elseif ($type == 'operation')
                                <img width="30px" class="icon-img d-inline-block" src="{{ url('images/icons/operation.svg') }}" />
                            @endif
                        </div>
                        <div class="line-body">
                            <h5>{{ trans('messages.automation.action.' . $type) }}</h5>
                            <p>{{ trans('messages.automation.action.' . $type . '.desc') }}</p>
                            @if ($type == 'condition' && $hasChildren == "true")
                                <p class="text-warning small mt-1">
                                    <i class="material-symbols-rounded">warning</i> {{ trans('messages.automation.action.can_not_add_condition') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        $('.action-select-operation').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            var url = '{!! action('Automation2Controller@operationSelect', $automation->uid) !!}';
            popup.load(url);
        });
    </script>

@endsection
