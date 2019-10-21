# Laravel Notification Channels For Msg91 SMS Notifications

## Installation

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

**SMS**

```php
// your Notification
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91SMS)
        ->content("Message is this");
}
```

**OTP**

```php
// your Notification
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91OTP);
        // ->resend(); // if this is a resend otp notification
}
```

When sending notifications via the `msg91` channel, the notification system will automatically look for a `phone_number` attribute on the notifiable entity. If you would like to customize the phone number the notification is delivered to, define a `routeNotificationForMsg91` method on the entity as suggested on [laravel docs](https://laravel.com/docs/5.8/notifications#routing-sms-notifications).

**NOTE**: Phone number must be in international format i.e. it must include the country code.

## Verify OTP

This package include the [Laravel Msg91 Client][client-laravel], so you can use all the api provided by that package
like verify an OTP, sending otp without using notification channel etc.

[client]: https://github.com/craftsys/msg91-php
[client-configuration]: https://github.com/craftsys/msg91-php#configuration
[client-laravel]: https://github.com/craftsys/msg91-laravel
