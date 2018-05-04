<?php

Route::get('/', 'TodoController@index');
Route::get('/api/list', 'TodoController@list');
Route::post('/api/add', 'TodoController@add');
Route::post('/api/remove/{id}', 'TodoController@remove');
Route::post('/api/removeall', 'TodoController@removeAll');
Route::post('/api/changestate/{id}', 'TodoController@changeState');
Route::post('/api/changetitle/{id}', 'TodoController@changeTitle');