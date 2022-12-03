@extends('layouts.popup.medium')

@section('content')
<div class="modal-header">    
    <h5 class="modal-title"><strong>{{ trans('messages.campaign.spam_score') }}</strong>
    
    @if ($score['result'] == true)
        <span class="label label-flat bg-active">{{ trans('messages.campaign.score.passed') }} {{ $score['score'] }}</span>
    @else
        <span class="badge badge-warning">{{ trans('messages.campaign.score.failed') }} {{ $score['score'] }}</span>
    @endif
    
    </h5>
    <button role="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mc_section mb-0">
        @if ($score['result'] == true)
            <p>{{ trans('messages.campaign.score.passed.intro') }}</p>
        @else
            <p>{{ trans('messages.campaign.score.failed.intro') }}</p>
        @endif
        
        <table class="table table-bodered-heading">
            <thead>
                <tr class="">
                    <th>{{ trans('messages.campaign.score.test') }}</th>
                    <th>{{ trans('messages.campaign.score') }}</th>
                    <th>{{ trans('messages.campaign.score.description') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($score['details'] as $test)
                    <tr class="campaign-row-{{ $test['status'] }}">
                        <td style="white-space: nowrap"><i class="icon icon-check me-1" title="{{ $test['status'] }}"></i> {{ $test['rule'] }}</td>
                        <td title="{{ $test['status'] }}">{{ $test['score'] }}</td>
                        <td>{{ $test['desc'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="mt-20">Powered by SpamAssassin™</p>
    </div>
</div>
@endsection