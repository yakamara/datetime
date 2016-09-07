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

class Date extends AbstractDateTime
{
    public function __construct(string $date = 'today', \DateTimeZone $timezone = null)
    {
        parent::__construct(self::stripTime($date), new \DateTimeZone('UTC'));
    }

    public static function create($year, $month, $day): self
    {
        return new self("$year-$month-$day");
    }

    public function __toString(): string
    {
        return $this->format('Y-m-d');
    }

    public function add($interval)
    {
        return parent::add($interval)->setTime(0, 0, 0);
    }

    public function sub($interval)
    {
        return parent::sub($interval)->setTime(0, 0, 0);
    }

    public function modify($modify)
    {
        return parent::modify($modify)->setTime(0, 0, 0);
    }

    public function setTime($hour, $minute, $second = 0)
    {
        return parent::setTime(0, 0, 0);
    }

    public function setTimestamp($timestamp)
    {
        return parent::setTimestamp($timestamp)->setTime(0, 0, 0);
    }

    public function setTimezone($timezone)
    {
        return $this;
    }

    private static function stripTime(string $dateTime): string
    {
        if (in_array($dateTime, ['today', 'now', ''], true)) {
            return 'today';
        }

        if ('@' === substr($dateTime, 0, 1)) {
            return gmdate('Y-m-d 00:00:00', substr($dateTime, 1));
        }

        return preg_replace('/\d{1,2}:\d{1,2}:\d{1,2}(?:\.\d+)?/', '00:00:00', $dateTime);
    }
}
