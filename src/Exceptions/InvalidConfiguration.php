<?php

namespace NotificationChannels\Wunderlist\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function configurationNotSet()
    {
        return new static('In order to send notification via Wunderlist you need to add credentials in the `wunderlist` key of `config.services`.');
    }
}
