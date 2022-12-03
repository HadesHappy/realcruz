@extends('layouts.core.install')

@section('title', trans('messages.finish'))

@section('content')


        <h4 class="text-primary fw-600 mb-3"><span class="material-symbols-rounded me-2">
task_alt
</span> Congratulations, you've successfully installed Datanex Labs LLC Marketing Application </h4>
            
        Remember that all your configurations were saved in <strong class="text-semibold">[APP_ROOT]/.env</strong> file. You can change it when needed.
        <br /><br />
        Now, you can go to your Admin Panel at <a class="text-semibold" href="{{ action('Admin\HomeController@index') }}">{{ action('Admin\HomeController@index') }}</a>
        <br /><br />
        <!-- If you are having problems or suggestions, please visit <a class="text-semibold" href="http://acellemail.com" target="_blank">acellemail.com official website</a>
        <br><br> -->

        Thank you for chosing Datanex Labs LLC.
        <div class="clearfix"><!-- --></div>      
<br />

@endsection
