<?php

namespace Craftsys\Tests\Notifications\Channels;

use Craftsys\Msg91\Client;
use Craftsys\Notifications\Channels\Msg91Channel;
use Craftsys\Notifications\Messages\Msg91OTP;
use Craftsys\Notifications\Messages\Msg91SMS;
use Craftsys\Tests\Notifications\TestCase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class Msg91ChannelTest extends TestCase
{
    protected $container = [];

    protected $config = [
        'key' => '123123123123'
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = [];
    }

    public function test_send_sms()
    {
        $notifiable = new Msg91TestNotifiable;
        $notification = new Msg91SMSTestNotification;

        $client = new Client($this->config);
        $client->setHttpClient($this->createMockHttpClient());

        $channel = new Msg91Channel($client);
        $channel->send($notifiable, $notification);
        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
    }

    public function test_send_otp()
    {
        $notifiable = new Msg91TestNotifiable;
        $notification = new Msg91OTPTestNotification;

        $client = new Client($this->config);
        $client->setHttpClient($this->createMockHttpClient());

        $channel = new Msg91Channel($client);
        $channel->send($notifiable, $notification);

        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
    }

    public function test_resend_otp()
    {
        $notifiable = new Msg91TestNotifiable;
        $notification = new Msg91OTPResendTestNotification;

        $client = new Client($this->config);
        $client->setHttpClient($this->createMockHttpClient());

        $channel = new Msg91Channel($client);
        $channel->send($notifiable, $notification);

        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
    }
    protected function createMockHttpClient(
        $status_code = 200,
        $body = [
            "type" => "success", "message" => "Send successfully"
        ]
    ): HttpClient {
        $history = Middleware::history($this->container);
        $mock = new MockHandler([
            new Response($status_code, [], json_encode($body)),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new HttpClient(['handler' => $handler]);
        return $client;
    }
}

class Msg91TestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';
}

class Msg91SMSTestNotification extends Notification
{
    public function toMsg91($notifiable)
    {
        return (new Msg91SMS('this is my message'))
            ->flow("12123")
            ->variable('name', 'Sudhir M');
    }
}

class Msg91OTPTestNotification extends Notification
{
    public function toMsg91($notifiable)
    {
        return (new Msg91OTP)->otp();
    }
}

class Msg91OTPResendTestNotification extends Notification
{
    public function toMsg91($notifiable)
    {
        return (new Msg91OTP)->otp()->resend();
    }
}
