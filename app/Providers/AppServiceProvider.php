<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        //
        Schema::defaultStringLength(255);

        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }


        Validator::extend('pwd_equal', function ($attribute, $value, $parameters, $validator) {
            $first_pwd = Arr::get($validator->getData(), $parameters[0]);
            $last_pwd = $value;
            return $first_pwd == $last_pwd;
        });

        Validator::extend('regex2', function ($attribute, $value, $parameters, $validator) {
            return !preg_match($parameters[0], $value);
        });
    }
}
