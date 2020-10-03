<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Torann\GeoIP\Facades\GeoIP;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your  API!
|
*/

Route::middleware(['auth:api','request.log'])->get('/user', function (Request $request,\App\Repositories\UserRepository $userRepository) {
    $user = $userRepository->getUserById($request->user()->id);
    return $user->load('accesses');
});

Route::group(['middleware' => ['auth:api','request.log'],
              'prefix' => 'user'], function () {
    Route::post('/revoke_token','AuthController@revokeToken');

    Route::group(['prefix' => 'security',
                  'namespace' => 'userSettings'], function () {
        Route::get('settings', function (Request $request) {
            return $request->user()->load('settings');
        });
        Route::get('logs', function (Request $request) {
            return $request->user()->load('logs');
        });
        Route::post('logs/clear', 'SettingsController@clearLogs');
        Route::post('change/password', 'SettingsController@changePassword');
        Route::post('change/backup_email', 'SettingsController@changeBackupEmail');
        Route::post('change/settings', 'SettingsController@changeSettings');
    });
});

Route::group([
    'prefix' => '2fa',], function () {
    Route::post('/send', 'TwoFAController@sendEmail');
});


Route::group([
    'prefix' => 'recovery',
    'namespace' => 'passwordRecovery'
], function () {
    Route::post('/send', 'recoveryController@sendEmail');
    Route::post('/check', 'recoveryController@checkEmail');
    Route::post('/confirm', 'recoveryController@confirmResetToken');
    Route::post('/change', 'recoveryController@changePassword');
    Route::post('/verify', 'recoveryController@verify');
});
Route::group([
    'prefix' => 'dashboard',
    'middleware' => ['auth:api','access:admin']
], function() {
    Route::get('users', 'UsersController@index');

    Route::get('user/{id}/edit', 'UsersController@edit');
    Route::put('user/{id}', 'UsersController@update');
    Route::post('user', 'UsersController@store');
    Route::delete('users', 'UsersController@destroy');
    Route::post('users/mass_update','UsersController@massUpdate');
});

Route::group([
    'prefix' => 'dashboard',
    'middleware' => ['auth:api']
],
    function () {
        Route::get('departments', 'department\DepartmentController@index');
        Route::get('department/{id}/edit', 'department\DepartmentController@edit')->middleware('access:admin');
        Route::post('department/update', 'department\DepartmentController@update')->middleware('access:admin');
        Route::post('department', 'department\DepartmentController@store')->middleware('access:admin');
        Route::post('department/delete', 'department\DepartmentController@destroy')->middleware('access:admin');
        Route::post('department/search', 'department\DepartmentController@search')->middleware('access:admin');
});

Route::group([
    'prefix' => 'profile',
    'middleware' => ['auth:api']
],
    function () {
        Route::post('save/data', 'profile\ProfileController@update');
        Route::post('save/avatar', 'profile\AvatarController@update');
});

Route::middleware(['auth:api'])->get('/client_logout', function (Request $request) {
    $request->user()->token()->revoke();
    $userTokens = $request->user()->tokens;
    foreach($userTokens as $token) {
        $token->revoke();
    }

    return 'https://team1-group-project.azurewebsites.net/client_logout';
});
