<?php

namespace NotificationChannels\Wunderlist\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static('Wunderlist responded with an error: `'.$response->getBody()->getContents().'`');
    }
}
