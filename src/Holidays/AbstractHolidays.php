<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara\Holidays;

use Yakamara\Date;

abstract class AbstractHolidays implements HolidaysInterface
{
    private $holidays = [];
    private $easter = [];

    public function isHoliday(\DateTimeInterface $dateTime): bool
    {
        return in_array(Date::createFromDateTime($dateTime), $this->getHolidays($dateTime->format('Y')));
    }

    public function isWorkday(\DateTimeInterface $dateTime): bool
    {
        if (!in_array($dateTime->format('w'), $this->getWorkdays())) {
            return false;
        }

        return !$this->isHoliday($dateTime);
    }

    public function getHolidays($year): array
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

    public function getEaster($year): Date
    {
        if (isset($this->easter[$year])) {
            return $this->easter[$year];
        }

        $G = $year % 19;
        $C = (int) ($year / 100);
        $H = (int) ($C - (int) ($C / 4) - (int) ((8 * $C + 13) / 25) + 19 * $G + 15) % 30;
        $I = (int) $H - (int) ($H / 28) * (1 - (int) ($H / 28) * (int) (29 / ($H + 1)) * ((int) (21 - $G) / 11));
        $J = ($year + (int) ($year / 4) + $I + 2 - $C + (int) ($C / 4)) % 7;
        $L = $I - $J;
        $m = 3 + (int) (($L + 40) / 44);
        $d = $L + 28 - 31 * ((int) ($m / 4));
        $y = $year;

        return $this->easter[$year] = Date::create($y, $m, $d);
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
