<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara\DateTime\Range;

use Yakamara\DateTime\Date;
use Yakamara\DateTime\DateTime;
use Yakamara\DateTime\DateTimeInterface;

/**
 * @method Date getStart()
 * @method Date getEnd()
 */
class DateRange extends AbstractDateTimeRange implements \IteratorAggregate, \Countable
{
    public function __construct(Date $start, Date $end)
    {
        parent::__construct($start, $end);
    }

    public function toDateTimeRange(): DateTimeRange
    {
        return new DateTimeRange($this->getStart()->toDateTime(), $this->getEnd()->addDays(1)->toDateTime());
    }

    public function toUtcDateTimeRange(): DateTimeRange
    {
        return new DateTimeRange($this->getStart()->toDateTime()->toUtc(), $this->getEnd()->addDays(1)->toDateTime()->toUtc());
    }

    public function isWholeYear(): bool
    {
        if (!$this->isSameYear()) {
            return false;
        }

        return $this->getStart()->isStartOfYear() && $this->getEnd()->isEndOfYear();
    }

    public function isWholeMonth(): bool
    {
        if (!$this->isSameMonth()) {
            return false;
        }

        return $this->getStart()->isStartOfMonth() && $this->getEnd()->isEndOfMonth();
    }

    public function isWholeDay(): bool
    {
        return $this->isSameDay();
    }

    public function contains(DateTimeInterface $dateTime): bool
    {
        if ($dateTime instanceof DateTime) {
            return $this->toDateTimeRange()->contains($dateTime);
        }

        return $this->getStart() <= $dateTime && $this->getEnd() >= $dateTime;
    }

    public function count(): int
    {
        return $this->getStart()->diff($this->getEnd())->days + 1;
    }

    /**
     * @return \Generator|Date[]
     */
    public function getIterator(): \Generator
    {
        for ($date = $this->getStart(); $date <= $this->getEnd(); $date = $date->addDays(1)) {
            yield $date;
        }
    }
}
