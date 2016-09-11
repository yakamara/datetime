<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara\DateTime\Range;

use Yakamara\DateTime\DateTimeInterface;
use Yakamara\DateTime\Holidays\HolidaysInterface;

interface DateTimeRangeInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getStart(): DateTimeInterface;

    /**
     * @return DateTimeInterface
     */
    public function getEnd(): DateTimeInterface;

    /**
     * @return \DateInterval
     */
    public function diff(): \DateInterval;

    /**
     * @param HolidaysInterface|null $holidays
     *
     * @return int
     */
    public function diffWorkdays(HolidaysInterface $holidays = null): int;

    /**
     * @return bool
     */
    public function isSameYear(): bool;

    /**
     * @return bool
     */
    public function isSameMonth(): bool;

    /**
     * @return bool
     */
    public function isSameDay(): bool;

    /**
     * @return bool
     */
    public function isWholeYear(): bool;

    /**
     * @return bool
     */
    public function isWholeMonth(): bool;

    /**
     * @return bool
     */
    public function isWholeDay(): bool;

    /**
     * @param DateTimeInterface $dateTime
     *
     * @return bool
     */
    public function contains(DateTimeInterface $dateTime): bool;
}
