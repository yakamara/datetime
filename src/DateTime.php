<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara\DateTime;

/**
 * @method DateTime modify($modify)
 */
class DateTime extends AbstractDateTime
{
    public static function create($year, $month, $day, $hour = 0, $minute = 0, $second = 0): self
    {
        return new self("$year-$month-$day $hour:$minute:$second");
    }

    public static function createUtc(string $dateTime): self
    {
        return new self($dateTime, self::getUtcTimezone());
    }

    public static function createFromUtc(string $dateTime): self
    {
        return self::createUtc($dateTime)->setTimezone(self::getDefaultTimezone());
    }

    public static function now(): self
    {
        return new self();
    }

    public function formatIso(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function formatIsoDate(): string
    {
        return $this->format('Y-m-d');
    }

    public function formatIsoTime(): string
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
        $dateTime = $this->setTimezone(self::getUtcTimezone());

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

    public function isStartOfYear(): bool
    {
        return '01-01 00:00:00' === $this->format('m-d H:i:s');
    }

    public function isStartOfMonth(): bool
    {
        return '01 00:00:00' === $this->format('d H:i:s');
    }

    public function isMidnight(): bool
    {
        return '00:00:00' === $this->formatIsoTime();
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
