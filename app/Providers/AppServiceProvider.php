<?php

namespace App\Providers;

use Encore\Admin\Config\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Classes\LangClass;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Config::load();
        Schema::defaultStringLength(191);
        $lang = new LangClass();
        \Lang::addLines($lang->vars, \App::getLocale());
        /*foreach ($lang->vars as $key=>$val){
            Blade::directive('_'.$key, function () use ($val) { return $val; });
            ${'_'.$key} = $val;
            trans($key, $val);
        }*/

        //dump($lang->vars);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        //$this->app->register(App\Classes\LangClass::class);
    }

// 

//
}
