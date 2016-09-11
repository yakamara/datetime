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
 * @method DateTime getStart()
 * @method DateTime getEnd()
 */
class DateTimeRange extends AbstractDateTimeRange
{
    public function __construct(DateTime $start, DateTime $end)
    {
        parent::__construct($start, $end);
    }

    public function toUtc(): self
    {
        return new self($this->getStart()->toUtc(), $this->getEnd()->toUtc());
    }

    public function toDateRange(): DateRange
    {
        $to = $this->getEnd();
        $to = $this->getStart() == $to ? $to : $to->addSeconds(-1);

        return new DateRange($this->getStart()->toDate(), $to->toDate());
    }

    public function isWholeYear(): bool
    {
        if (!$this->getStart()->isStartOfYear()) {
            return false;
        }

        return $this->getStart()->addYears(1) == $this->getEnd();
    }

    public function isWholeMonth(): bool
    {
        if (!$this->getStart()->isStartOfMonth()) {
            return false;
        }

        return $this->getStart()->addMonths(1) == $this->getEnd();
    }

    public function isWholeDay(): bool
    {
        if (!$this->getStart()->isMidnight()) {
            return false;
        }

        return $this->getStart()->addDays(1) == $this->getEnd();
    }

    public function contains(DateTimeInterface $dateTime): bool
    {
        if ($dateTime instanceof Date) {
            return $this->toDateRange()->contains($dateTime);
        }

        return $this->getStart() <= $dateTime && $this->getEnd() > $dateTime;
    }
}
