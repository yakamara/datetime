<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara;

/**
 * @method DateTime modify(string $modify)
 */
class DateTime extends AbstractDateTime
{
    public static function create($year, $month, $day, $hour = 0, $minute = 0, $second = 0): self
    {
        return new self("$year-$month-$day $hour:$minute:$second");
    }

    public static function createFromUtc(string $dateTime): self
    {
        /** @var self $dateTime */
        $dateTime = new self($dateTime, new \DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        return $dateTime;
    }

    public static function now(): self
    {
        return new self();
    }

    public function toIso(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function toIsoDate(): string
    {
        return $this->format('Y-m-d');
    }

    public function toIsoTime(): string
    {
        return $this->format('H:i:s');
    }

    public function toDate(): Date
    {
        return Date::createFromDateTime($this);
    }

    public function toUtc(): self
    {
        /** @var self $dateTime */
        $dateTime = $this->setTimezone(new \DateTimeZone('UTC'));

        return $dateTime;
    }

    public function getHour(): int
    {
        return (int) $this->format('H');
    }

    public function getMinute(): int
    {
        return (int) $this->format('i');
    }

    public function getSecond(): int
    {
        return (int) $this->format('s');
    }

    public function addHours(int $hours): self
    {
        return $this->modify($hours.' hours');
    }

    public function addMinutes(int $minutes): self
    {
        return $this->modify($minutes.' minutes');
    }

    public function addSeconds(int $seconds): self
    {
        return $this->modify($seconds.' seconds');
    }
}
