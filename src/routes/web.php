<?php

// Status Routes
Route::get('status', 'Enigma\Status\Controllers\PageController@show')->middleware('web')->name('status');