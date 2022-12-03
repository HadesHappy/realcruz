@extends('layouts.core.page')

@section('title', "")

@section('content')
  <div class="row">
    <div class="col-md-12 tex-center">
      <form action="{{ action('MailListController@embeddedFormSubscribe', $list->uid) }}" method="POST" class="form-validate-jqueryz">
        @foreach (request()->all() as $key => $value)
          @if (is_array($value))
                        @foreach ($value as $cvalue)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $cvalue }}" />
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                    @endif
        @endforeach

                @if (Acelle\Model\Setting::get('embedded_form_recaptcha') == 'yes')
                    <input type="hidden" name="hasCaptcha" value="1" />
                    <div style="margin: 100px auto; width: 300px;text-align:center">
                        <!-- hCaptcha -->
                        @if (\Acelle\Model\Setting::getCaptchaProvider() == 'hcaptcha')
                            @php
                                $hcaptcha = \Acelle\Hcaptcha\Client::initialize();
                            @endphp
                            {!! $hcaptcha->renderFormHtml($errors) !!}
                        @else
                            {!! Acelle\Library\Tool::showReCaptcha($errors) !!}
                        @endif
                        
                        <br />
                        <input type="submit" class="btn btn-primary" value="{{ trans('messages.confirm') }}" />
                        <a href="javascript:;" onclick="window.history.back()" class="btn btn-default ml-2">{{ trans('messages.return_back') }}</a>
                    </div>
                @endif
      </form>
    </div>
  </div>

    @if (Acelle\Model\Setting::get('embedded_form_recaptcha') == 'no')
        <script>
            $(document).ready(function() {
                $('form').submit();
            });
        </script>
    @endif

@endsection
