<?php

// Client View Groups
Route::group(['middleware' => ['web'], 'namespace' => '\{{ author_class }}\{{ name_class }}\Controllers'], function () {
    Route::get('plugins/{{ author }}/{{ name }}', 'DashboardController@index');
});
