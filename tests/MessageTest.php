<?php

namespace NotificationChannels\Wunderlist\Test;

use DateTime;
use Illuminate\Support\Arr;
use NotificationChannels\Wunderlist\Exceptions\CouldNotCreateMessage;
use NotificationChannels\Wunderlist\WunderlistMessage;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\Wunderlist\WunderlistMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new WunderlistMessage('');
    }

    /** @test */
    public function it_accepts_a_title_when_constructing_a_message()
    {
        $message = new WunderlistMessage('Title');

        $this->assertEquals('Title', Arr::get($message->toArray(), 'title'));
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = WunderlistMessage::create('Title');

        $this->assertEquals('Title', Arr::get($message->toArray(), 'title'));
    }

    /** @test */
    public function it_can_set_the_title()
    {
        $this->message->title('TicketTitle');

        $this->assertEquals('TicketTitle', Arr::get($this->message->toArray(), 'title'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_string()
    {
        $date = new DateTime('tomorrow');
        $this->message->due('tomorrow');

        $this->assertEquals($date->format(DateTime::ATOM), Arr::get($this->message->toArray(), 'due_date'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_datetime()
    {
        $date = new DateTime('tomorrow');
        $this->message->due($date);

        $this->assertEquals($date->format(DateTime::ATOM), Arr::get($this->message->toArray(), 'due_date'));
    }

    /** @test */
    public function it_can_set_the_ticket_as_starred()
    {
        $this->message->starred();

        $this->assertEquals(true, Arr::get($this->message->toArray(), 'starred'));
    }

    /** @test */
    public function it_has_default_messages_not_starred()
    {
        $this->assertEquals(false, Arr::get($this->message->toArray(), 'starred'));
    }

    /** @test */
    public function it_can_set_the_ticket_as_completed()
    {
        $this->message->completed();

        $this->assertEquals(true, Arr::get($this->message->toArray(), 'completed'));
    }

    /** @test */
    public function it_has_default_messages_not_completed()
    {
        $this->assertEquals(false, Arr::get($this->message->toArray(), 'completed'));
    }

    /**
     * @test
     *
     * @dataProvider recurrenceProvider
     *
     * @param string $type
     * @param string $stringRepresentation
     */
    public function it_can_set_a_recurrence_type($type, $stringRepresentation)
    {
        $this->message->recurrenceType($type);

        $this->assertEquals($stringRepresentation, Arr::get($this->message->toArray(), 'recurrence_type'));
    }

    public function recurrenceProvider()
    {
        return [
            [WunderlistMessage::RECURRENCE_TYPE_DAY, 'day'],
            [WunderlistMessage::RECURRENCE_TYPE_WEEK, 'week'],
            [WunderlistMessage::RECURRENCE_TYPE_MONTH, 'month'],
            [WunderlistMessage::RECURRENCE_TYPE_YEAR, 'year'],
        ];
    }

    /** @test */
    public function it_throws_an_exception_when_the_recurrence_type_is_invalid()
    {
        $this->setExpectedException(CouldNotCreateMessage::class);

        $this->message->recurrenceType('foo');
    }
}
