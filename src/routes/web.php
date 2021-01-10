<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ldocs', 'namespace' => 'Mdemet\Ldocs\Http\Controllers'], function () {
    Route::get('/', 'LdocsController@index')->name('ldocs-index');
    Route::get('/discover', 'LdocsController@discoverClasses')->name('ldocs-discover-classes');
    Route::post('/ajax-save-description', 'LdocsController@ajaxSave')->name('ldocs-ajax');
});




