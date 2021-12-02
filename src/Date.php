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

use Yakamara\DateTime\Range\DateTimeRange;

class Date extends AbstractDateTime
{
    public function __construct(string $date = 'today', \DateTimeZone $timezone = null)
    {
        parent::__construct(self::stripTime($date), $timezone);
    }

    public static function create($year, $month, $day): self
    {
        return new self("$year-$month-$day");
    }

    public static function today(): self
    {
        static $today;

        return $today ?: $today = new self();
    }

    public static function yesterday(): self
    {
        static $yesterday;

        return $yesterday ?: $yesterday = new self('yesterday');
    }

    public static function tomorrow(): self
    {
        static $tomorrow;

        return $tomorrow ?: $tomorrow = new self('tomorrow');
    }

    public function formatIso(): string
    {
        return $this->format('Y-m-d');
    }

    public function formatIntl(int $format = null, int $timeFormat = null): string
    {
        if (!class_exists(\IntlDateFormatter::class)) {
            throw new \Exception(sprintf('%s can not be used without the intl extension.', __METHOD__));
        }

        return parent::formatIntl($format, $timeFormat ?? \IntlDateFormatter::NONE);
    }

    public function toDateTime(): DateTime
    {
        return DateTime::createFromDateTime($this);
    }

    public function toRange(): DateTimeRange
    {
        $dateTime = $this->toDateTime();

        return new DateTimeRange($dateTime, $dateTime->addDays(1));
    }

    public function toUtcRange(): DateTimeRange
    {
        $dateTime = $this->toDateTime()->toUtc();

        return new DateTimeRange($dateTime, $dateTime->addDays(1));
    }

    public function isStartOfYear(): bool
    {
        return '01-01' === $this->format('m-d');
    }

    public function isEndOfYear(): bool
    {
        return '12-31' === $this->format('m-d');
    }

    public function isStartOfMonth(): bool
    {
        return 1 === $this->getDay();
    }

    public function isEndOfMonth(): bool
    {
        return 1 === $this->addDays(1)->getDay();
    }

    public function add(\DateInterval $interval): \DateTimeImmutable
    {
        return parent::add($interval)->setTime(0, 0, 0);
    }

    public function sub(\DateInterval $interval): \DateTimeImmutable
    {
        return parent::sub($interval)->setTime(0, 0, 0);
    }

    public function modify(string $modify): \DateTimeImmutable|false
    {
        return parent::modify($modify)->setTime(0, 0, 0);
    }

    public function setTime(int $hour, int $minute, int $second = 0, int $microseconds = 0): \DateTimeImmutable
    {
        return parent::setTime(0, 0, 0, 0);
    }

    public function setTimestamp(int $timestamp): \DateTimeImmutable
    {
        return parent::setTimestamp($timestamp)->setTime(0, 0, 0);
    }

    public function setTimezone(\DateTimeZone $timezone): \DateTimeImmutable
    {
        return new self($this->formatIso(), $timezone);
    }

    private static function stripTime(string $dateTime): string
    {
        if (\in_array($dateTime, ['today', 'now', ''], true)) {
            return 'today';
        }

        if ('@' === substr($dateTime, 0, 1)) {
            return date('Y-m-d 00:00:00', (int) substr($dateTime, 1));
        }

        return preg_replace('/\d{1,2}:\d{1,2}(?::\d{1,2}(?:\.\d+)?)?/', '00:00:00', $dateTime);
    }
}
