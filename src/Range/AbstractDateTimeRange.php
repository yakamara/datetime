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

use Yakamara\DateTime\DateTimeInterface;
use Yakamara\DateTime\Holidays\HolidaysInterface;

abstract class AbstractDateTimeRange implements DateTimeRangeInterface
{
    /** @var DateTimeInterface */
    private $start;

    /** @var DateTimeInterface */
    private $end;

    public function __construct(DateTimeInterface $start, DateTimeInterface $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): DateTimeInterface
    {
        return $this->start;
    }

    public function getEnd(): DateTimeInterface
    {
        return $this->end;
    }

    public function diff(): \DateInterval
    {
        return $this->start->diff($this->end);
    }

    public function diffWorkdays(?HolidaysInterface $holidays = null): int
    {
        return $this->start->diffWorkdays($this->end, $holidays);
    }

    public function isSameYear(): bool
    {
        return $this->start->getYear() === $this->end->getYear();
    }

    public function isSameMonth(): bool
    {
        return $this->start->format('Y-m') === $this->end->format('Y-m');
    }

    public function isSameDay(): bool
    {
        return $this->start->format('Y-m-d') === $this->end->format('Y-m-d');
    }
}
