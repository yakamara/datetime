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

use Yakamara\Holidays;
use Yakamara\Holidays\HolidaysInterface;

/**
 * @method AbstractDateTime modify($modify)
 */
abstract class AbstractDateTime extends \DateTimeImmutable implements DateTimeInterface
{
    private static $defaultHolidays;

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return static
     */
    public static function createFromDateTime(\DateTimeInterface $dateTime): self
    {
        if ($dateTime instanceof static) {
            return $dateTime;
        }

        $class = static::getClass();

        return new $class($dateTime->format('Y-m-d H:i:s'));
    }

    /**
     * @param int $timestamp
     *
     * @return static
     */
    public static function createFromTimestamp(int $timestamp): self
    {
        $class = static::getClass();

        return new $class('@' . $timestamp);
    }

    /**
     * @param int|string|\DateTimeInterface $dateTime
     *
     * @return static
     */
    public static function createFromUnknown($dateTime): self
    {
        if (is_int($dateTime)) {
            return static::createFromTimestamp($dateTime);
        }

        if ($dateTime instanceof \DateTimeInterface) {
            return static::createFromDateTime($dateTime);
        }

        $class = static::getClass();

        return new $class($dateTime);
    }

    public function __toString(): string
    {
        return $this->toIso();
    }

    public function formatLocalized(string $format): string
    {
        return strftime($format, $this->getTimestamp());
    }

    public function getYear(): int
    {
        return (int) $this->format('Y');
    }

    public function getMonth(): int
    {
        return (int) $this->format('m');
    }

    public function getDay(): int
    {
        return (int) $this->format('d');
    }

    public function getWeekday(): int
    {
        return (int) $this->format('w');
    }

    public function addYears(int $years): DateTimeInterface
    {
        return $this->modify($years.' years');
    }

    public function addMonths(int $months): DateTimeInterface
    {
        return $this->modify($months.' months');
    }

    public function addWeeks(int $weeks): DateTimeInterface
    {
        return $this->modify($weeks.' weeks');
    }

    public function addDays(int $days): DateTimeInterface
    {
        return $this->modify($days.' days');
    }

    public function isWorkday(HolidaysInterface $holidays = null): bool
    {
        $holidays = $holidays ?: self::getDefaultHolidays();

        return $holidays->isWorkday($this);
    }

    public function isHoliday(HolidaysInterface $holidays = null): bool
    {
        $holidays = $holidays ?: self::getDefaultHolidays();

        return $holidays->isHoliday($this);
    }

    public function addWorkdays(int $days, HolidaysInterface $holidays = null): DateTimeInterface
    {
        $holidays = $holidays ?: self::getDefaultHolidays();

        $interval = \DateInterval::createFromDateString($days . ' days');
        $days = abs($days);
        $date = $this;

        for ($i = 0; $i < $days; ++$i) {
            do {
                $date = $date->add($interval);
            } while ($holidays->isHoliday($date));
        }

        return $date;
    }

    public function diffWorkdays(DateTimeInterface $date, HolidaysInterface $holidays = null): int
    {
        $holidays = $holidays ?: self::getDefaultHolidays();

        $date1 = Date::createFromDateTime($this);
        $date2 = Date::createFromDateTime($date);

        if ($date1 < $date2) {
            $date = $date2;
            $date2 = $date1;
            $date1 = $date;
        }

        for ($i = 0; $date2 < $date1; ++$i) {
            $date2->addWorkdays(1, $holidays);
        }

        return $i;
    }

    public static function setDefaultHolidays(HolidaysInterface $holidays)
    {
        self::$defaultHolidays = $holidays;
    }

    public static function getDefaultHolidays(): HolidaysInterface
    {
        if (!self::$defaultHolidays) {
            self::$defaultHolidays = new Holidays\Germany();
        }

        return self::$defaultHolidays;
    }

    private static function getClass(): string
    {
        $class = get_called_class();

        return __CLASS__ === $class ? DateTime::class : $class;
    }
}
