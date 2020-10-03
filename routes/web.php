<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/authorization{any}', 'SpaController@auth')->where('any', '^.*$');
Route::get('/{any}', 'SpaController@main')->where('any', '^(?!api)(?!logout)(?!storage)(?!client_logout)(?!avatars).*$')->middleware('auth');

Route::get('logout', 'AuthController@logout')->middleware('auth');
Route::post('login', 'AuthController@login');

Route::get('client_logout', function (\Illuminate\Http\Request $request) {
    $redirectTo = \Illuminate\Support\Facades\Redirect::back()->getTargetUrl();
    Auth::logout();

    return \Illuminate\Support\Facades\Redirect::to($redirectTo);
})->middleware('auth');
