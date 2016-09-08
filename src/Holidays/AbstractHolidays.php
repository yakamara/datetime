<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara\DateTime\Holidays;

use Yakamara\DateTime\Date;
use Yakamara\DateTime\DateTimeInterface;

abstract class AbstractHolidays implements HolidaysInterface
{
    private $holidays = [];
    private $easter = [];

    public function isHoliday(DateTimeInterface $dateTime): bool
    {
        return in_array(Date::createFromDateTime($dateTime), $this->getHolidays($dateTime->getYear()));
    }

    public function isWorkday(DateTimeInterface $dateTime): bool
    {
        if (!in_array($dateTime->getWeekday(), $this->getWorkdays())) {
            return false;
        }

        return !$this->isHoliday($dateTime);
    }

    public function getHolidays(int $year): array
    {
        if (isset($this->holidays[$year])) {
            return $this->holidays[$year];
        }

        $this->holidays[$year] = [];

        foreach ($this->getFixedHolidays() as list($month, $day)) {
            $this->holidays[$year][] = Date::create($year, $month, $day);
        }

        $easterBasedHolidays = $this->getEasterBasedHolidays();
        if ($easterBasedHolidays) {
            $easter = $this->getEaster($year);

            foreach ($this->getEasterBasedHolidays() as $diff) {
                $this->holidays[$year][] = $easter->modify($diff.' days');
            }
        }

        return $this->holidays[$year];
    }

    public function getEaster(int $year): Date
    {
        if (isset($this->easter[$year])) {
            return $this->easter[$year];
        }

        $firstDigits = intdiv($year, 100);
        $remain19 = $year % 19;

        $temp = intdiv($firstDigits - 15, 2) + 202 - 11 * $remain19;

        if (in_array($firstDigits, [21, 24, 25, 27, 28, 29, 30, 31, 32, 34, 35, 38], true)) {
            $temp -= 1;
        } elseif (in_array($firstDigits, [33, 36, 37, 39, 40], true)) {
            $temp -= 2;
        }

        $temp = $temp % 30;

        $tA = $temp + 21;
        if (29 === $temp) {
            --$tA;
        }
        if (28 === $temp && $remain19 > 10) {
            --$tA;
        }

        $tB = ($tA - 19) % 7;

        $tC = (40 - $firstDigits) % 4;
        if (3 === $tC) {
            ++$tC;
        }
        if ($tC > 1) {
            ++$tC;
        }

        $temp = $year % 100;
        $tD = ($temp + intdiv($temp, 4)) % 7;

        $tE = ((20 - $tB - $tC - $tD) % 7) + 1;
        $day = $tA + $tE;

        if ($day > 31) {
            $day = $day - 31;
            $month = 4;
        } else {
            $month = 3;
        }

        return $this->easter[$year] = Date::create($year, $month, $day);
    }

    protected function getFixedHolidays(): array
    {
        return [];
    }

    protected function getEasterBasedHolidays(): array
    {
        return [];
    }
}
