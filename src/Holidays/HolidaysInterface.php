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

interface HolidaysInterface
{
    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return bool
     */
    public function isHoliday(\DateTimeInterface $dateTime): bool;

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return bool
     */
    public function isWorkday(\DateTimeInterface $dateTime): bool;

    /**
     * @param int|string $year
     *
     * @return Date[]
     */
    public function getHolidays($year): array;

    /**
     * @return int[]
     */
    public function getWorkdays(): array;
}
