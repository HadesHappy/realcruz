<?php
    // convert html name to laravel name
    $var_name = str_replace('[]', '', $name);
    $var_name = str_replace('][', '.', $var_name);
    $var_name = str_replace('[', '.', $var_name);
    $var_name = str_replace(']', '', $var_name);

    // error
    $error = $errors->has($var_name) ? $errors->first($var_name) : null;
?>

@include('helpers.form_control.' . $type)