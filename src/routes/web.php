<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ldocs', 'namespace' => 'Mdemet\Ldocs\Http\Controllers'], function () {
    Route::get('/', 'LdocsController@index')->name('ldocs-index');
    Route::get('/edit', 'LdocsController@edit')->name('ldocs-edit');
    Route::get('/scan', 'LdocsController@scanProject')->name('ldocs-scan-project');
    Route::post('/ajax/save-description', 'LdocsController@ajaxSaveDescription')->name('ldocs-ajax-description');
    Route::post('/ajax/toggle-active', 'LdocsController@ajaxToggleActive')->name('ldocs-ajax-active');
});




