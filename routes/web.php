<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view(config('custom.front_template').'.welcome');
});

Route::get('/blogs', 'App\Http\Controllers\PagesController@getBlogs')->name('blogs');

Route::get('/blogs/{slug}', 'App\Http\Controllers\PagesController@getBlogContent')->name('blog.content');

Auth::routes();


Route::match(['get'], 'login', function () {
    return abort(404);
});
Route::get('/loginAdmin', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('loginAdmin', 'App\Http\Controllers\Auth\LoginController@login');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.home');

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin/', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin\\'], function () {

    Route::get('/mail_send_test', [
        'uses' => 'DashboardController@sendDummyMail',
        'as' => 'mail_tester',
    ]);
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
    Route::get('logs', ['as' => 'logs', 'uses' => 'DashboardController@showLog']);
    Route::post('logs/clear', ['as' => 'logs_clear', 'uses' => 'DashboardController@clearLog']);
    Route::get('activitylogs', ['as' => 'activitylogs', 'uses' => 'DashboardController@allActivityLogs']);

    Route::get('video-list/{portal_name}',['as'=>'videos', 'uses'=> 'DashboardController@getVideos']);
    Route::get('video/{video_link}',['as'=>'video_link', 'uses'=> 'DashboardController@getVideo'])->where('video_link', '^[^/]+$');
    
    Route::get('user_manual',['as'=>'user_manual', 'uses'=> 'DashboardController@getManual']);
    Route::get('user_manual/{manual_link}',['as'=>'user_manual_file', 'uses'=> 'DashboardController@getManualFile']);



    include __DIR__ . DIRECTORY_SEPARATOR . 'admin.php';

});

Route::group(['prefix' => 'profile', 'middleware' => ['auth']], function () {

    Route::get('/change-password', [
        'as' => 'change-password',
        'uses' => 'App\Http\Controllers\Auth\ProfileController@showPasswordForm'
    ]);

    Route::post('/change-password', [
        'as' => 'submit-password',
        'uses' => 'App\Http\Controllers\Auth\ProfileController@changePassword'
    ]);

    Route::resource('profile', 'App\Http\Controllers\Auth\ProfileController')->only('index');

});
