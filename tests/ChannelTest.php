<?php

namespace NotificationChannels\Wunderlist\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Wunderlist\Exceptions\CouldNotSendNotification;
use NotificationChannels\Wunderlist\Exceptions\InvalidConfiguration;
use NotificationChannels\Wunderlist\WunderlistChannel;
use NotificationChannels\Wunderlist\WunderlistMessage;
use Orchestra\Testbench\TestCase;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_notification()
    {
        $this->app['config']->set('services.wunderlist.key', 'WunderlistKey');

        $response = new Response(200);
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->with('https://a.wunderlist.com/api/v1/tasks',
                [
                    'body' => '{"title":"WunderlistName","assignee_id":null,"completed":null,"recurrence_type":null,"recurrence_count":null,"due_date":null,"starred":true,"list_id":12345}',
                    'headers' => [
                        'X-Client-ID' => 'WunderlistKey',
                        'X-Access-Token' => 'NotifiableToken',
                        'Content-Type' => 'application/json',
                    ],
                ])
            ->andReturn($response);
        $channel = new WunderlistChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_it_is_not_configured()
    {
        $this->setExpectedException(InvalidConfiguration::class);

        $client = new Client();
        $channel = new WunderlistChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_it_could_not_send_the_notification()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->app['config']->set('services.wunderlist.key', 'WunderlistKey');

        $response = new Response(500);
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->andReturn($response);
        $channel = new WunderlistChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return int
     */
    public function routeNotificationForWunderlist()
    {
        return [
            'token' => 'NotifiableToken',
            'list_id' => 12345,
        ];
    }
}


class TestNotification extends Notification
{
    public function toWunderlist($notifiable)
    {
        return
            (new WunderlistMessage('WunderlistName'))
                ->starred();
    }
}
