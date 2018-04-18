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

interface HolidaysInterface
{
    public function isHoliday(DateTimeInterface $dateTime): bool;

    public function isWorkday(DateTimeInterface $dateTime): bool;

    /**
     * @return Date[]
     */
    public function getHolidays(int $year): array;

    /**
     * @return int[]
     */
    public function getWorkdays(): array;
}
