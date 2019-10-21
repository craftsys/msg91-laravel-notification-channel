<?php

namespace Craftsys\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Craftsys\Msg91\Client;
use Craftsys\Msg91\Msg91Message;
use Craftsys\Notifications\Messages\Msg91OTP;
use Craftsys\Notifications\Messages\Msg91SMS;

class Msg91Channel
{
    /**
     * The Msg91 Client
     * @var \Craftsys\Msg91\Client
     */
    protected $msg91;

    public function __construct(Client $msg91)
    {
        $this->msg91 = $msg91;
    }

    /**
     * Send the notification
     * @return null|\Craftsys\Msg91\Response
     */
    public function send($notifiable, Notification $notification)
    {
        $to =  $notifiable->routeNotificationFor('msg91', $notification) ?: $notifiable->phone_number;

        if (!$to) return;

        $message = $notification->toMsg91($notifiable);

        if (is_string($message)) {
            $message = new Msg91Message($message);
        }

        $payload = $message->toArray();

        // send whatever meet the truth
        switch (true) {
            case $message instanceof Msg91SMS:
                // send the sms
                return $this->msg91->sms($payload)->to($to)->send();
            case $message instanceof Msg91OTP:
            case array_key_exists("otp", $payload):
                // send the otp
                if (method_exists($message, "isResending")) {
                    if ($message->isResending()) {
                        return $this->msg91->otp($payload)->to($to)->resend();
                    }
                }
                return $this->msg91->otp($payload)->to($to)->send();
            default:
                // the default will send a sms
                return $this->msg91->sms($payload)->to($to)->send();
        }
    }
}
