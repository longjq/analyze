<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/see', function(){
    return view('welcome');
});

Route::get('/', function () {
    return redirect('/dash');
});

Route::get('/login', 'LoginController@index');
Route::post('/login', 'LoginController@postLogin');
Route::get('/logout', 'LoginController@logout');

Route::group(['middleware' => 'auth'], function () {
    // 后台首页
    Route::get('/dash', 'AdminController@dash');

    Route::get('/dash/new', 'RefreshController@userNews');
    Route::get('/dash/hot', 'RefreshController@userHots');
    Route::get('/dash/live', 'RefreshController@userLives');
    Route::get('/dash/cache', 'RefreshController@cacheIntoDB');


    // 字段导出用户id
    Route::match(['get', 'post'], '/user/data', 'AdminController@userData');
    Route::match(['get', 'post'], '/user/data_export', 'AdminController@userDataExport');
    Route::get('/user/download_query', 'AdminController@downloadUserQuery');

    // 应用用户统计
    Route::match(['get', 'post'], '/app/users', 'AppController@users');
    Route::get('/app/download_users', 'AppController@downloadUsers');

    // 应用用户查询
    Route::match(['get','post'], '/app/packages', 'AppController@packages');
    Route::get('/app/package_detail', 'AppController@packageDetail');

    // 历史安装和卸载
    Route::match(['get', 'post'], '/app/events', 'AppController@events');
    Route::get('/app/download_events', 'AppController@downloadEvents');

    // 新增用户图表
    Route::match(['get', 'post'], '/charts/users_new', 'ChartController@usersNew');
    // 活跃用户
    Route::match(['get', 'post'], '/charts/users_hot', 'ChartController@usersHot');
    // 存活率
    Route::match(['get', 'post'], '/charts/users_list', 'ChartController@usersList');

});


Route::get('/t', function (\Illuminate\Http\Request $request) {
    
    $users = new App\Models\Assistant\User();
    $userState= new App\Models\Assistant\UserState();
    $events = new App\Models\Assistant\UserEvent();
    $locations = new App\Models\Assistant\UserLocation();
    $packages = new App\Models\Assistant\UserSnapshots();


    $agent = new \App\Core\ReportAgent();
    $users_data = $users->skip(5)->take(5)->get();
    $userStates_data = $userState->skip(5)->take(5)->get();
    $events_data = $events->take(5)->get();
    $locations_data = $locations->take(5)->get();
    $packages_data = $packages->take(5)->get();

    foreach ($events_data as $event){
        $agent->info($event);
    }foreach ($locations_data as $location){
        $agent->info($location);
    }foreach ($packages_data as $package){
        $agent->info($package);
    }


    foreach ($users_data as $user){
        $agent->info($user);
    } foreach ($userStates_data as $userState){
        $agent->info($userState);
    }



});


Route::get('/create', 'LoginController@create');
Route::match(['get','post'],'/test', 'LoginController@test');



