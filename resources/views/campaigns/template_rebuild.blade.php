@extends('layouts.builder')

@section('title', trans('messages.create_template'))

@section('content')

        <div class="right">
            <form action="{{ action('CampaignController@template', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
                {{ csrf_field() }}
                <input type="hidden" name="template_source" value="builder" class="required" />
                <textarea class="hide template_content" name="html"></textarea>
                <div class="">
                    <button class="btn btn-primary me-1">{{ trans('messages.save') }}</button>
                    <a href="{{ action('CampaignController@templatePreview', $campaign->uid) }}" class="btn bg-slate">{{ trans('messages.cancel') }}</a>
                </div>
            </form>
        </div>
        <div class="left">
            <h1>{{ $campaign->name }}: {{ trans('messages.build_template') }}</h1>
        </div>

@endsection

@section('template_content')

    {!! $campaign->getTemplateContent() !!}
    
@endsection
