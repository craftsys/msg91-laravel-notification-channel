# Laravel Notification Channels For Msg91 SMS Notifications

Laravel notification channel for Msg91 API (wrapper around [Laravel Msg91 Client][client-laravel])

## Table of Contents

-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Phone Number](#phone_number)
-   [Advanced Usage](#advanced_usage)
-   [Related](#related)
-   [Acknowledgements](#acknowledgements)

## Installation

**prerequisite**

-   php^7.1
-   laravel^5|^6|^7

The package is tested for 5.8+,^6.0,^7.0 only. If you find any bugs for laravel (5.0< >5.8), please file an issue.

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

If a notification should being sent via SMS/OTP, you should define a `toMsg91` method on the notification class. This method should return a `Craftsys\Notifications\Messages\Msg91SMS` or `Craftsys\Notifications\Messages\Msg91OTP` message instance based on the need of sending message or sending an OTP.

**SMS**

```php
// add "msg91" into the channels list
public function via ($notifiable) {
    return ['msg91'];
}
// for msg91
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91SMS)
        ->content("Message is this");
}
```

**OTP**

```php
// add "msg91" into the channels list
public function via ($notifiable) {
    return ['msg91'];
}
// for msg91
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91OTP);
        // ->resend(); // if this is a resend otp notification
}
```

## Phone Number

When sending notifications via `msg91` channel, the notification system will automatically look for a `phone_number` attribute on the notifiable entity. If you would like to customize the phone number where the notification is delivered to, define a `routeNotificationForMsg91` method on the $notifiable entity as suggested on [laravel docs](https://laravel.com/docs/5.8/notifications#routing-sms-notifications).

**NOTE**: Phone number must be in **International Format** i.e. it must include the country code.

## Advanced Usage

These messages (Msg91SMS and Msg91OTP) extend `\Craftsys\Msg91\Options`, so all configuration methods are available when crafting your notification message. These are all optional and you can use them in any order. e.g.


```php
public function toMsg91($notifiable)
{
    return (new \Craftsys\Notifications\Messages\Msg91OTP)
        ->digits(6) // set the digits in otp message
        ->expiresInMinutes(1) // change the exipery time
        ->message("Your verification code is ##OTP##") // custom otp template
        ->from("SNCBD"); // set a custom sender id
}
```

## Verify OTP

This package include the [Laravel Msg91 Client][client-laravel-verify-otp], so you can use all the api provided by that package
like verify an OTP, sending otp without using notification channel etc.

# Related

- [Msg91 Php Client](https://github.com/craftsys/msg91-php)
- [Msg91 Laravel](https://github.com/craftsys/msg91-laravel)
- [Msg91 Api Docs](https://docs.msg91.com/collection/msg91-api-integration/5/pages/139)

# Acknowledgements

- [Nexmo Laravel Notification Channel](https://github.com/laravel/nexmo-notification-channel)

[client]: https://github.com/craftsys/msg91-php
[client-configuration]: https://github.com/craftsys/msg91-php#configuration
[client-laravel]: https://github.com/craftsys/msg91-laravel
[client-laravel-verify-otp]: https://github.com/craftsys/msg91-laravel#verify-otp


