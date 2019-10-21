<?php

namespace Craftsys\Notifications;

use Craftsys\Msg91\Client;
use Craftsys\Notifications\Channels\Msg91Channel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

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
                return new Msg91Channel(
                    $this->app->make(Client::class)
                );
            });
        });
    }
}
