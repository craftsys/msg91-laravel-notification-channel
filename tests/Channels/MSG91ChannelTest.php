<?php

namespace Craftsys\Tests\Notifications\Channels;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Msg91Message;
use Craftsys\Notifications\Channels\Msg91Channel;
use Craftsys\Tests\Notifications\TestCase;
use Mockery as m;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Msg91ChannelTest extends TestCase
{
    public function test_sms_is_send_via_msg91()
    {
        $notifiable = new Msg91TestNotifiable;
        $notification = new Msg91SMSTestNotification;

        $msg91 = m::mock(Client::class);
        $channel = new Msg91Channel($msg91);

        $msg91->shouldReceive('sms->to->send');
        $channel->send($notifiable, $notification);
    }

    public function test_sms_with_string_only_is_send_via_msg91()
    {
        $notifiable = new Msg91TestNotifiable;
        $notification = new Msg91SMSTestNotificationStringReturn;

        $msg91 = m::mock(Client::class);
        $channel = new Msg91Channel($msg91);

        $msg91->shouldReceive('sms->to->send');
        $channel->send($notifiable, $notification);
    }

    public function test_otp_is_send_via_msg91()
    {
        $notifiable = new Msg91TestNotifiable;
        $notification = new Msg91OTPTestNotification;

        $msg91 = m::mock(Client::class);
        $channel = new Msg91Channel($msg91);

        $msg91->shouldReceive('otp->to->send');
        $channel->send($notifiable, $notification);
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
        return new Msg91Message('this is my message');
    }
}

class Msg91SMSTestNotificationStringReturn extends Notification
{
    public function toMsg91($notifiable)
    {
        return 'this is my message';
    }
}

class Msg91OTPTestNotification extends Notification
{
    public function toMsg91($notifiable)
    {
        return (new Msg91Message)->otp();
    }
}
