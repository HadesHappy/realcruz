@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <form id="automationCreate" action="{{ action("Automation2Controller@wizard") }}" method="POST" class="form-validate-jqueryz">
                {{ csrf_field() }}            

                @foreach(request()->all() as $key => $value)
                    @if (is_array($value))
                        @foreach($value as $key2 => $value2)
                            @if (is_array($value2))
                                @foreach($value2 as $key3 => $value3)
                                    <input type="hidden" name="{{ $key }}[{{ $key2 }}][]" value="{{ $value3 }}" />
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}[{{ $key2 }}]" value="{{ $value2 }}" />
                            @endif
                        @endforeach 
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                    @endif
                    
                @endforeach
        
                <h1 class="mb-20">{{ trans('messages.automation.create_automation') }}</h1>
            
                <p class="mb-10">{{ trans('messages.automation.name_your_automation') }}</p>
                
                <div class="row mb-4">
                    <div class="col-md-8">
                        @include('helpers.form_control', [
                            'type' => 'text',
                            'class' => '',
                            'label' => '',
                            'name' => 'name',
                            'value' => $automation->name,
                            'help_class' => 'automation',
                            'rules' => $automation->rules(),
                        ])
                    </div>
                </div>
                
                <div class="text-center">
                    <button class="btn btn-secondary mt-20">{{ trans('messages.automation.get_started') }}</button>
                </div>
                    
            </form>
                
        </div>
    </div>
        
    <script>
        $('#automationCreate').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var url = form.attr('action');
            
            // loading effect
            createAutomationPopup.loading();
            
            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                       createAutomationPopup.loadHtml(res.responseText);
                    }
                },
                success: function (res) {
                    createAutomationPopup.hide();
                    
                    addMaskLoading(res.message, function() {
                        setTimeout(function() {
                            window.location = res.url;
                        }, 1000);
                    });
                }
            }).always(function() {
                createAutomationPopup.unmask();
            });
        });
    </script>
@endsection