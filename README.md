# Wunderlist notifications channel for Laravel 5.3 [WIP]

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/wunderlist.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/wunderlist)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/wunderlist/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/wunderlist)
[![StyleCI](https://styleci.io/repos/65379321/shield)](https://styleci.io/repos/65379321)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/9015691f-130d-4fca-8710-72a010abc684.svg?style=flat-square)](https://insight.sensiolabs.com/projects/9015691f-130d-4fca-8710-72a010abc684)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/wunderlist.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/wunderlist)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/wunderlist.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/wunderlist)

This package makes it easy to create [Wunderlist tasks](https://developers.wunderlist.com/) with Laravel 5.3.

## Contents

- [Installation](#installation)
    - [Setting up the Wunderlist service](#setting-up-the-wunderlist-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/wunderlist
```

### Setting up the Wunderlist service

Create a [new Wunderlist App](https://developer.wunderlist.com/apps/new).

Add your Wunderlist Client-ID to your `config/services.php`:

```php
// config/services.php

    'wunderlist' => [
        'key' => env('WUNDERLIST_API_KEY'),
    ]
```


## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Wunderlist\WunderlistChannel;
use NotificationChannels\Wunderlist\WunderlistMessage;
use Illuminate\Notifications\Notification;

class ProjectCreated extends Notification
{
    public function via($notifiable)
    {
        return [WunderlistChannel::class];
    }

    public function toWunderlist($notifiable)
    {
        return WunderlistMessage::create()
            ->name("Wunderlist Ticket Name")
            ->description("This is the Wunderlist ticket description")
            ->top()
            ->due('tomorrow');
    }
}
```

In order to let your Notification know which Wunderlist user and Wunderlist list you are targeting, add the `routeNotificationForWunderlist` method to your Notifiable model.

This method needs to return an array containing the access token of the authorized Wunderlist user and the list ID of the Wunderlist list to add the ticket to.

```php
public function routeNotificationForWunderlist()
{
    return [
        'token' => 'NotifiableAccessToken',
        'list_id' => 12345,
    ];
}
```

### Available methods

- `name('')`: Accepts a string value for the Wunderlist ticket name.
- `description('')`: Accepts a string value for the Wunderlist ticket description.
- `top()`: Moves the Wunderlist ticket to the top.
- `bottom()`: Moves the Wunderlist ticket to the bottom.
- `position('')`: Accepts an integer for a specific Wunderlist ticket position.
- `due('')`: Accepts a string or DateTime object for the Wunderlist ticket due date.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email m.pociot@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
