<?php

namespace Craftsys\MSG91Client\Laravel\Notifications;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Craftsys\MSG91Client\Client;

class Msg91ChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('msg91', function ($app) {
                return new Channels\MSG91Client(
                    $this->app['config']['services.msg91'],
                    $this->app->make(Client::class)
                );
            });
        });
    }
}
