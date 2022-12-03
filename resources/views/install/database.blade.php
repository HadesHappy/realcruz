@extends('layouts.core.install')

@section('title', trans('messages.database'))

@section('content')

<h3 class="text-primary"><span class="material-symbols-rounded">
dns
</span> {{ trans('messages.database_configuration') }}</h3>

@if (isset($connect_error))
	<div class="alert alert-danger">
		{{ $connect_error }}
	</div>
@endif

<form action="{{ action('InstallController@database') }}" method="POST" class="form-validate-jqueryz">
	{!! csrf_field() !!}
	
		<div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'hostname',
                'value' => (isset($database["hostname"]) ? $database["hostname"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'port',
                'value' => (isset($database["port"]) ? $database["port"] : "3306"),
								'placeholder' => '3306',
                'help_class' => 'install',
                'rules' => $rules
            ]) 
        </div>
    </div>
			
		<div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'username',
                'value' => (isset($database["username"]) ? $database["username"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'password',
                'value' => (isset($database["password"]) ? $database["password"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
        </div>
    </div>
	
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'database_name',
                'value' => (isset($database["database_name"]) ? $database["database_name"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
                 
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'tables_prefix',
                'value' => (isset($database["tables_prefix"]) ? $database["tables_prefix"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
                 
        </div>
    </div>
    <hr >
    <div class="text-end">                                    
        <button type="submit" class="btn btn-primary save-button">{!! trans('messages.save') !!} <span class="material-symbols-rounded">
east
</span></button>
    </div>
</form>

<script>

    $(function() {
        $('.save-button').on('click', function() {
            addButtonMask($(this));
        });
    });

</script>
@endsection
