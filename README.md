# Laravel Notification Channels For Msg91 SMS Notifications

Laravel notification channel for Msg91 API (wrapper around [Laravel Msg91 Client][client-laravel])

## Table of Contents

-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
    -   [SMS](#sms)
    -   [OTP](#otp)
    -   [Verify OTP](#verify-otp)
    -   [Routing SMS Notifications](#routing-sms-notifications)
-   [Related](#related)
-   [Acknowledgements](#acknowledgements)

## Installation

**prerequisite**

-   php^7.1
-   laravel^5|^6|^7|^8

The package is tested for 5.8+,^6.0,^7.0,^8.0 only. If you find any bugs for laravel (5.0< >5.8), please file an issue.

```bash
composer require craftsys/msg91-laravel-notification-channel
```

> If you just want to integrate Msg91 api in Laravel without notification channel, please use [Msg91 Laravel][client-laravel] instead.

## Configuration

Next, you will need to add a few configuration options to your `config/services.php` configuration file. You may copy the example configuration below to get started:

```php
// along with other services
'msg91' => [
    'key' => env("MSG91_KEY")
]
```

All available configuration can be found at [msg91-php client's configuration page][client-configuration]

## Usage

If a notification supports being sent as an SMS, you should define a `toMsg91` method on the notification class. This method will receive a `$notifiable` entity and should return a `Craftsys\Notifications\Messages\Msg91SMS` or `Craftsys\Notifications\Messages\Msg91OTP` instance based on your need to sending message or sending an OTP.

**NOTE**: Phone number must be in international format i.e. it must include the country code.

### SMS

```php
// your Notification
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91SMS)
        ->flow("your_flow_id");
}

// with variables
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91SMS)
        ->flow("your_flow_id")
        ->variable('name', $notifiable->name)
        ->variable('status', "Overdue");
}
```

### OTP

```php
// your Notification
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91OTP)
        ->from('12123');
        // ->otp(12123) // set a custom otp
        // ->resend() // if this is a resend otp notification
}
```

### Verify OTP

This package include the [Laravel Msg91 Client][client-laravel], so you can use all the api provided by that package
like verify an OTP, sending otp without using notification channel etc.

### Routing SMS Notification

When sending notifications via the `msg91` channel, the notification system will automatically look for a
`phone_number` attribute on the notifiable entity. If you would like to customize the phone number the notification
is delivered to, define a `routeNotificationForMsg91` method on the entity as suggested on [laravel
docs](https://laravel.com/docs/5.8/notifications#routing-sms-notifications).

```php
class User {
    use Notifiable;
    /**
     * Route notifications for the Msg91 channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMsg91 ($notification) {
        return $this->phone;
    }
}
```

You can also set the recipient(s) when composing your message in the `toMsg91` method of your notification as
follows:

```php
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91SMS)
        ->to(91992123123) // you can also pass an array for bulk notifications
        ->flow('your_flow_id');
}
```

## Related

-   [Msg91 Php Client](https://github.com/craftsys/msg91-php)
-   [Msg91 Laravel](https://github.com/craftsys/msg91-laravel)
-   [Msg91 Api Docs](https://docs.msg91.com/collection/msg91-api-integration/5/pages/139)

## Acknowledgements

-   [Nexmo Laravel Notification Channel](https://github.com/laravel/nexmo-notification-channel)

[client]: https://github.com/craftsys/msg91-php
[client-configuration]: https://github.com/craftsys/msg91-php#configuration
[client-laravel]: https://github.com/craftsys/msg91-laravel
