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

class Germany extends AbstractHolidays
{
    public function getWorkdays(): array
    {
        return [1, 2, 3, 4, 5];
    }

    protected function getFixedHolidays(): array
    {
        return [
            [1, 1],   // Neujahr
            [5, 1],   // Tag der Arbeit
            [10, 3],  // Tag der Deutschen Einheit
            [12, 25], // 1. Weihnachtstag
            [12, 26], // 2. Weihnachtstag
        ];
    }

    protected function getEasterBasedHolidays(): array
    {
        return [
            -2, // Karfreitag
            0,  // Ostersonntag
            1,  // Ostermontag
            39, // Christi Himmelfahrt
            50, // Pfingstmontag
        ];
    }
}
