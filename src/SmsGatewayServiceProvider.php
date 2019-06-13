<?php

namespace qlixes\SmsGateway;

use Illuminate\Support\ServiceProvider;

class SmsGatewayServiceProvider extends ServiceProvider
{
    var $vendor;

    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('smsgateway', function($app) {
            return new EnvayaSmsGateway();
        });
    }
}
