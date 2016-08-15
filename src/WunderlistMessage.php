<?php

namespace NotificationChannels\Wunderlist;

use DateTime;
use NotificationChannels\Wunderlist\Exceptions\CouldNotCreateMessage;

class WunderlistMessage
{
    /** @var string */
    protected $title;

    /** @var bool */
    protected $completed;

    /** @var bool */
    protected $starred;

    /** @var string */
    protected $recurrenceType;

    /** @var string|null */
    protected $due;

    /** @var int|null */
    protected $assigneeId;

    /** @var int|null */
    protected $recurrenceCount;

    const RECURRENCE_TYPE_DAY = 'day';
    const RECURRENCE_TYPE_WEEK = 'week';
    const RECURRENCE_TYPE_MONTH = 'month';
    const RECURRENCE_TYPE_YEAR = 'year';

    /**
     * @param string $title
     *
     * @return static
     */
    public static function create($title)
    {
        return new static($title);
    }

    /**
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Set the ticket title.
     *
     * @param $title
     *
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the ticket assignee_id.
     *
     * @param int $assigneeId
     *
     * @return $this
     */
    public function assigneeId($assigneeId)
    {
        $this->assigneeId = $assigneeId;

        return $this;
    }

    /**
     * Set the ticket recurrence_count.
     *
     * @param int $recurrenceCount
     *
     * @return $this
     */
    public function recurrenceCount($recurrenceCount)
    {
        $this->recurrenceCount = $recurrenceCount;

        return $this;
    }

    /**
     * Set the ticket recurrent_type property.
     *
     * @param string $recurrenceType
     *
     * @throws CouldNotCreateMessage
     *
     * @return $this
     */
    public function recurrenceType($recurrenceType)
    {
        $allowedRecurrenceTypes = [self::RECURRENCE_TYPE_DAY, self::RECURRENCE_TYPE_WEEK, self::RECURRENCE_TYPE_MONTH, self::RECURRENCE_TYPE_YEAR];

        if (! in_array($recurrenceType, $allowedRecurrenceTypes)) {
            throw CouldNotCreateMessage::invalidRecurrenceType($recurrenceType);
        }

        $this->recurrenceType = $recurrenceType;

        return $this;
    }

    /**
     * Set the ticket as starred.
     *
     * @return $this
     */
    public function starred()
    {
        $this->starred = true;

        return $this;
    }

    /**
     * Set the ticket as completed.
     *
     * @return $this
     */
    public function completed()
    {
        $this->completed = true;

        return $this;
    }

    /**
     * Set the card position due date.
     *
     * @param string|DateTime $due
     *
     * @return $this
     */
    public function due($due)
    {
        if (! $due instanceof DateTime) {
            $due = new DateTime($due);
        }

        $this->due = $due->format(DateTime::ATOM);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'assignee_id' => $this->assigneeId,
            'completed' => $this->completed,
            'recurrence_type' => $this->recurrenceType,
            'recurrence_count' => $this->recurrenceCount,
            'due_date' => $this->due,
            'starred' => $this->starred,
        ];
    }
}
