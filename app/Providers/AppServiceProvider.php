<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //视图间共享数据
        //$last = \App\Libraries\Cache::getInstance()->getString('last');
        //$last = \App\Models\Cache::where('key','last_query')->lists('value');
        //view()->share('last',$last[0]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
