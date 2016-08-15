# Wunderlist notifications channel for Laravel 5.3

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/wunderlist.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/wunderlist)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/wunderlist/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/wunderlist)
[![StyleCI](https://styleci.io/repos/65743131/shield)](https://styleci.io/repos/65743131)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/11716c52-99b4-412b-b68c-b78e0f18f844.svg?style=flat-square)](https://insight.sensiolabs.com/projects/11716c52-99b4-412b-b68c-b78e0f18f844)
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
        return WunderlistMessage::create('Another channel bites the dust')
            ->starred()
            ->due('tomorrow');
    }
}
```

In order to let your notification know which Wunderlist user and Wunderlist list you are targeting, add the `routeNotificationForWunderlist` method to your Notifiable model.

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

- `title('')`: Accepts a string value for the Wunderlist ticket title.
- `assigneeId('')`: Accepts a string value for the Wunderlist assignee id.
- `recurrenceCount('')`: Accepts an integer value for the ticket recurrence count.
- `recurrenceType('')`: Accepts one of these values for the recurrence type: `WunderlistMessage::RECURRENCE_TYPE_DAY`, `WunderlistMessage::RECURRENCE_TYPE_WEEK`, `WunderlistMessage::RECURRENCE_TYPE_MONTH`,`WunderlistMessage::RECURRENCE_TYPE_YEAR`  
- `starred()`: Marks the Wunderlist ticket as starred.
- `completed()`: Marks the Wunderlist ticket as completed.
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
