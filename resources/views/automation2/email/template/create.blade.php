@extends('layouts.popup.large')

@section('content')    
        
    @include('automation2.email._tabs', ['tab' => 'template'])
    
    <div class="row">
        <div class="col-md-10">
            <h5 class="mb-3">{{ trans('messages.automation.template.email_content') }}</h5>                
            <p>{{ trans('messages.automation.template.email_content.intro') }}</p>
            
            <div class="row">
                <div class="col-md-10">
                    <ul class="hover-list">
                        <li class="template-start" data-url="{{ action('Automation2Controller@templateLayout', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}"
                        >
                            <img width="35px" class="icon-img d-inline-block me-4" src="{{ url('images/icons/plus.svg') }}" />
                            <div class="list-body">
                                <label>{{ trans('messages.automation.template.from_layout') }}</label>
                                <p class="text-muted">{{ trans('messages.automation.template.from_layout.intro') }}</p>
                            </div>
                            <div class="list-action">
                                <button
                                    class="btn btn-secondary"
                                >
                                    {{ trans('messages.automation.template.start') }}
                                </button>
                            </div>
                        </li>
                        <li class="template-start" data-url="{{ action('Automation2Controller@templateUpload', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}"
                        >
                            <img width="35px" class="icon-img d-inline-block me-4" src="{{ url('images/icons/upload.svg') }}" />
                            <div class="list-body">
                                <label>{{ trans('messages.automation.template.upload') }}</label>
                                <p class="text-muted">{{ trans('messages.automation.template.upload.intro') }}</p>
                            </div>
                            <div class="list-action">
                                <button
                                    class="btn btn-secondary template-start"
                                >
                                    {{ trans('messages.automation.template.start') }}
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        
    <script>
        $(document).ready(function() {
        
            $('.template-start').click(function() {
                var url = $(this).attr('data-url');
                
                popup.load(url);
            });
        
        });
    </script>
@endsection
