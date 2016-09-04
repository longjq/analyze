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
    $package = new \App\Models\Package();
    $packages = $package->lists()->take(15)->get();

    return '123';
});

Route::get('/', function () {
    return redirect('/dash');
});

Route::get('/login', 'LoginController@index');
Route::post('/login', 'LoginController@postLogin');
Route::get('/logout', 'LoginController@logout');

// Route::group(['middleware' => 'auth'], function () {
    // 后台首页
    Route::get('/dash', 'AdminController@dash');

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
    // Route::match(['get', 'post'], '/app/events_history', 'AppController@eventsHistory');

    Route::match(['get','post'], '/event/log', 'EventLogController@index');

    // 新增用户图表
    Route::match(['get', 'post'], '/charts/users_new', 'ChartController@usersNew');
    // 活跃用户
    Route::match(['get', 'post'], '/charts/users_hot', 'ChartController@usersHot');
    // 存活率
    Route::match(['get', 'post'], '/charts/users_list', 'ChartController@usersList');

// });




Route::get('/create', 'LoginController@create');
Route::match(['get','post'],'/test', 'LoginController@test');

ini_set('memory_limit', '1024M');
ini_set('max_execution_time',0);

Route::get('/c2e',function(){
    $start = microtime(true);
    // echo $start.'<br/>';
    $items = \App\Models\UserSnapshot::skip(1000)->take(100)->get();

    foreach($items as $item){
        \App\Core\ReportAgent::getInstance()->info($item);
    }
    print_r(round(microtime(true) - $start, 3));
});

Route::get('/savees',function(){

    $start = microtime(true);
    // echo $start.'<br/>';
    for($i=0;$i<1;$i++){
        \App\Libraries\Queue::getInstance()->run('user_events', 10000);
    }
    $timeSignature = [
        'datetime' => date('Y-m-d H:i:s'),
        'expend_time' => round((microtime(true) - $start), 3)
    ];
    \App\Libraries\Queue::getInstance()->expendTime(json_encode($timeSignature));
    print_r(round(microtime(true) - $start, 3));
});

Route::get('/f',function(){
    $start = microtime(true);

    $myfile = fopen("C:/Users/Administrator/Desktop/user_snapshots.log", "r") or die("Unable to open file!");
    while(!feof($myfile)) {
        $item = fgets($myfile);
        $item = json_decode($item, true);
        if($item){
            \App\Models\UserSnapshot::create([
                'user_id' => $item['@fields']['ctxt_user_id'],
                'md5' => $item['@fields']['ctxt_md5'],
                'snapshot' => $item['@fields']['ctxt_snapshot'],
                'snapshot_time' => $item['@fields']['ctxt_snapshot_time'],
                'created_at' => $item['@fields']['ctxt_created_at'],
                'updated_at' => $item['@fields']['ctxt_updated_at'],
            ]);
        }
    }
    fclose($myfile);

    print_r(round(microtime(true) - $start, 3));
});






Route::get('/test/ping', function(){
    \App\Libraries\Queue::getInstance()->run('analysis_caches', 100);
});



Route::get('/test/xxx',function(){
//    $str = 'analysis_local_user_events';
//
//    dd(str_replace('analysis_local_','',$str));
    $items = \App\Models\UserSnapshot::all();

    foreach( $items as $item ){
        \App\Core\ReportAgent::getInstance()->info($item);
    }
});







































