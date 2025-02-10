<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //check that app is local
       if ($this->app->isLocal()) {
        //if local register your services you require for development
     // $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }else{
                //else register your services you require for production
            $this->app['request']->server->set('HTTPS', true);
        }
        //Schema::defaultStringLength(191);
        $data_notif= DB::select('select tipe, judul, isi from notification
        where is_aktif=1 order by judul');
        //dd($data_notification);

        if(count($data_notif) > 0){
            view()->share('data_notification',[$data_notif[0]]);
        }
        
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
    }
}
