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

use Yakamara\DateTime\Holidays\HolidaysInterface;

interface DateTimeInterface extends \DateTimeInterface
{
    public function __toString(): string;

    public function formatIso(): string;

    public function formatLocalized(string $format): string;

    public function formatIntl(int $format = null, int $timeFormat = null): string;

    public function toMutable(): \DateTime;

    public function getYear(): int;

    public function getMonth(): int;

    public function getDay(): int;

    public function getWeekday(): int;

    /**
     * @return static
     */
    public function addYears(int $years): self;

    /**
     * @return static
     */
    public function addMonths(int $months): self;

    /**
     * @return static
     */
    public function addWeeks(int $weeks): self;

    /**
     * @return static
     */
    public function addDays(int $days): self;

    /**
     * @param null|HolidaysInterface $holidays
     *
     * @return bool
     */
    public function isWorkday(HolidaysInterface $holidays = null): bool;

    /**
     * @param null|HolidaysInterface $holidays
     *
     * @return bool
     */
    public function isHoliday(HolidaysInterface $holidays = null): bool;

    /**
     * @param int                    $days
     * @param null|HolidaysInterface $holidays
     *
     * @return static
     */
    public function addWorkdays(int $days, HolidaysInterface $holidays = null): self;

    /**
     * @param self                   $date
     * @param null|HolidaysInterface $holidays
     *
     * @return int
     */
    public function diffWorkdays(self $date, HolidaysInterface $holidays = null): int;

    /**
     * @param string $modify
     *
     * @return static
     */
    public function modify($modify);

    /**
     * @param \DateInterval $interval
     *
     * @return static
     */
    public function add($interval);

    /**
     * @param \DateInterval $interval
     *
     * @return static
     */
    public function sub($interval);

    /**
     * @param \DateTimeInterface $datetime2
     * @param bool               $absolute
     *
     * @return \DateInterval
     */
    public function diff($datetime2, $absolute = false);

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return static
     */
    public function setDate($year, $month, $day);

    /**
     * @param int $year
     * @param int $week
     * @param int $day
     *
     * @return static
     */
    public function setISODate($year, $week, $day = 1);

    /**
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $microseconds
     *
     * @return static
     */
    public function setTime($hour, $minute, $second = 0, $microseconds = 0);

    /**
     * @param int $timestamp
     *
     * @return static
     */
    public function setTimestamp($timestamp);

    /**
     * @param \DateTimeZone $timezone
     *
     * @return static
     */
    public function setTimezone($timezone);
}
