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

use Yakamara\Holidays\HolidaysInterface;

interface DateTimeInterface extends \DateTimeInterface
{
    public function __toString(): string;

    public function toIso(): string;

    public function formatLocalized(string $format): string;

    public function getYear(): int;

    public function getMonth(): int;

    public function getDay(): int;

    public function getWeekday(): int;

    public function addYears(int $years): self;

    public function addMonths(int $months): self;

    public function addWeeks(int $weeks): self;

    public function addDays(int $days): self;

    public function isWorkday(HolidaysInterface $holidays = null): bool;

    public function isHoliday(HolidaysInterface $holidays = null): bool;

    public function addWorkdays(int $days, HolidaysInterface $holidays = null): self;

    public function diffWorkdays(\DateTimeInterface $date, HolidaysInterface $holidays = null);
}
