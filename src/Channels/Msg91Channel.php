<?php

namespace Craftsys\Notifications\Channels;

use Craftsys\Msg91\Client;
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
    public function send($notifiable, $notification)
    {
        $to =  $notifiable->routeNotificationFor('msg91', $notification) ?: $notifiable->phone_number;

        $message = $notification->toMsg91($notifiable);

        if (is_string($message)) {
            // cast as SMS
            $message = new Msg91SMS($message);
        }

        $payload = $message->toArray();

        // send whatever meet the truth
        switch (true) {
            case $message instanceof Msg91SMS:
                // send the sms
                return $this->msg91->sms()->to($to)->options($message)->send();
            case $message instanceof Msg91OTP:
            case array_key_exists("otp", $payload):
                // send the otp
                if (method_exists($message, "isResending")) {
                    if ($message->isResending()) {
                        return $this->msg91->otp()->to($to)->options($message)->resend();
                    }
                }
                return $this->msg91->otp()->to($to)->options($message)->send();
            default:
                // the default will send a sms
                return $this->msg91->sms()->to($to)->options($message)->send();
        }
    }
}
