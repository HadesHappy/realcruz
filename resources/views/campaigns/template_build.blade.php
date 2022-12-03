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
                <a href="{{ action('CampaignController@template', $campaign->uid) }}" class="btn bg-slate">{{ trans('messages.cancel') }}</a>
            </div>
        </form>
    </div>
    <div class="left">
        <h1>{{ $campaign->name }}: {{ trans('messages.build_template') }}</h1>
    </div>

    <script>
        $(document).ready(function() {

            @foreach($elements as $element)
                insertElement("{{ $element }}");
            @endforeach

        });
    </script>

@endsection

@section('template_content')

    <content>
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/res_email.css') }}" />
        <center class="wrapper">
            <div class="webkit">
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="center">
                <tr>
                <td>
                <![endif]-->
                <table class="outer right-box" align="center">
                    <tr><td></td></tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </div>
        </center>
    </content>
  
@endsection
