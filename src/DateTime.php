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

    public function __toString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function toUtc(): self
    {
        /** @var self $dateTime */
        $dateTime = $this->setTimezone(new \DateTimeZone('UTC'));

        return $dateTime;
    }
}
