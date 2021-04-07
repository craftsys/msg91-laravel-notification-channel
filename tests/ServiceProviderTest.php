<?php

namespace Craftsys\Tests\Notifications;

use Craftsys\Msg91\Client;
use Craftsys\Notifications\Channels\Msg91Channel;
use Illuminate\Notifications\ChannelManager;

class ServiceProviderTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.msg91.key', 'my_api_key');
    }


    /**
     * Test that a channel is register with desired name
     *
     * @return void
     */
    public function testChannelIsRegistered()
    {
        /** @var \Illuminate\Notifications\ChannelManager $manager */
        $manager = app(ChannelManager::class);
        $channel = $manager->channel('msg91');
        $this->assertInstanceOf(Msg91Channel::class, $channel);
    }
}
