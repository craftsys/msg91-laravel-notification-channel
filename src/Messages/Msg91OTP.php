<?php

namespace Craftsys\Notifications\Messages;

use Craftsys\Msg91\OTP\Options;

/**
 * OTP message
 */
class Msg91OTP extends Options
{
    /**
     * Are we resending this otp ?
     * @var bool
     */
    protected $resend = false;

    /**
     * Set the we this is a resend OTP message
     * @return $this
     */
    public function resend()
    {
        $this->resend = true;
        return $this;
    }

    /**
     * Check if this is a resend otp message
     * @return bool
     */
    public function isResending()
    {
        return $this->resend;
    }
}
