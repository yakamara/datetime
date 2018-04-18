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
    public function getStart(): DateTimeInterface;

    public function getEnd(): DateTimeInterface;

    public function diff(): \DateInterval;

    public function diffWorkdays(?HolidaysInterface $holidays = null): int;

    public function isSameYear(): bool;

    public function isSameMonth(): bool;

    public function isSameDay(): bool;

    public function isWholeYear(): bool;

    public function isWholeMonth(): bool;

    public function isWholeDay(): bool;

    public function contains(DateTimeInterface $dateTime): bool;
}
