<?php
    $label = isset($label) ? $label : (Lang::has('messages.'.$name) ? trans('messages.'.$name) : '');
    $var_name = str_replace('[]', '', $name);
    $var_name = str_replace('][', '.', $var_name);
    $var_name = str_replace('[', '.', $var_name);
    $var_name = str_replace(']', '', $var_name);
    $classes = (isset($rules) && isset($rules[$var_name])) ? ' '.str_replace('|', ' ', $rules[$var_name]) : '';
    $required = (isset($rules) && isset($rules[$var_name]) && in_array('required', explode('|', $rules[$var_name]))) ? true : '';
?>

@if ($type == 'checkbox' || $type == 'checkbox3' || $type == 'radio3')
    @include('helpers._' . $type)
@elseif (!empty($errors))
    <div class="form-group{{ $errors->has($var_name) ? ' has-error' : '' }} control-{{ $type }}">
        @if (!empty($label) && $type != 'checkbox2' && $type != 'mc_checkbox')
            <label>
                {!! $label !!}
                @if ($required)
                    <span class="text-danger">*</span>
                @endif
                @if (isset($check_all_none))
                    &nbsp;&nbsp;&nbsp;
                    <a href="#all" class="checkboxes_check_all">{{ trans('messages.all') }}</a>
                    | <a href="#none" class="checkboxes_check_none">{{ trans('messages.none') }}</a>
                @endif
            </label>
        @endif

        @if (!empty($prefix))
            <span class="prefix">
                {!! $prefix !!}
            </span>
        @endif

        @include('helpers._' . $type)

        @if (!empty($quick_note))
            <span class="quick_note small">
                {!! $quick_note !!}
            </span>
        @endif

        @if (!empty($subfix))
            <span class="subfix">
                {!! $subfix !!}
            </span>
        @endif

        @if (isset($help_class) && Lang::has('messages.' . $help_class . '.' . $name . '.help'))
            <div class="help alert alert-info">
                {!! trans('messages.' . $help_class . '.' . $name . '.help') !!}
            </div>
        @endif
        
        @if (isset($help))
            <div class="help alert alert-info">
                {!! $help !!}
            </div>
        @endif

        @if ($errors->has($var_name))
            <span class="help-block">
                {{ $errors->first($var_name) }}
            </span>
        @endif
    </div>
@endif
