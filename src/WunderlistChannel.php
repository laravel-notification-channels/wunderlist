<?php

namespace NotificationChannels\Wunderlist;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use NotificationChannels\Wunderlist\Exceptions\CouldNotSendNotification;
use NotificationChannels\Wunderlist\Events\MessageWasSent;
use NotificationChannels\Wunderlist\Events\SendingMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Wunderlist\Exceptions\InvalidConfiguration;

class WunderlistChannel
{
    const API_ENDPOINT = 'https://a.wunderlist.com/api/v1/tasks';

    /** @var Client */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Wunderlist\Exceptions\InvalidConfiguration
     * @throws \NotificationChannels\Wunderlist\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $routing = collect($notifiable->routeNotificationFor('Wunderlist'))) {
            return;
        }

        $key = config('services.wunderlist.key');

        if (is_null($key)) {
            throw InvalidConfiguration::configurationNotSet();
        }

        $shouldSendMessage = event(new SendingMessage($notifiable, $notification), [], true) !== false;

        if (! $shouldSendMessage) {
            return;
        }

        $wunderlistParameters = $notification->toWunderlist($notifiable)->toArray();

        $response = $this->client->post(self::API_ENDPOINT, [
            'body' => json_encode(Arr::set($wunderlistParameters, 'list_id', (int) $routing->get('list_id'))),
            'headers' => [
                'X-Client-ID' => $key,
                'X-Access-Token' => $routing->get('token'),
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        event(new MessageWasSent($notifiable, $notification));
    }
}
