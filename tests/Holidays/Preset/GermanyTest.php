<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara\DateTime\Tests\Holidays\Preset;

use PHPUnit\Framework\TestCase;
use Yakamara\DateTime\Date;
use Yakamara\DateTime\DateTime;
use Yakamara\DateTime\DateTimeInterface;
use Yakamara\DateTime\Holidays\Preset\Germany;

final class GermanyTest extends TestCase
{
    /**
     * @dataProvider provideIsHoliday
     */
    public function testIsHoliday(bool $expected, DateTimeInterface $input): void
    {
        self::assertSame($expected, (new Germany())->isHoliday($input));
    }

    public function provideIsHoliday()
    {
        return [
            [false, Date::create(2016, 9, 8)],
            [false, DateTime::create(2016, 9, 10, 19, 30)],
            [true, Date::create(2017, 5, 1)],
            [true, DateTime::create(2021, 10, 3, 5, 5)],
        ];
    }

    /**
     * @dataProvider provideIsWorkday
     */
    public function testIsWorkday(bool $expected, DateTimeInterface $input): void
    {
        self::assertSame($expected, (new Germany())->isWorkday($input));
    }

    public function provideIsWorkday()
    {
        return [
            [true, Date::create(2016, 9, 8)],
            [false, DateTime::create(2016, 9, 10, 19, 30)],
            [false, Date::create(2017, 5, 1)],
            [false, DateTime::create(2021, 10, 3, 5, 5)],
        ];
    }

    public function getWorkdays(): void
    {
        self::assertSame([1, 2, 3, 4, 5], (new Germany())->getWorkdays());
    }

    public function testGetHolidays(): void
    {
        $holidays = (new Germany())->getHolidays(2017);

        self::assertCount(10, $holidays);

        $dates = [
            [1, 1],
            [4, 14],
            [4, 16],
            [4, 17],
            [5, 1],
            [5, 25],
            [6, 5],
            [10, 3],
            [12, 25],
            [12, 26],
        ];

        foreach ($dates as [$month, $day]) {
            self::assertContainsEquals(Date::create(2017, $month, $day), $holidays);
        }
    }

    /**
     * @dataProvider provideGetEaster
     */
    public function testGetEaster(Date $date): void
    {
        self::assertEquals($date, (new Germany())->getEaster($date->getYear()));
    }

    public function provideGetEaster()
    {
        return [
            [Date::create(1990, 4, 15)],
            [Date::create(2016, 3, 27)],
            [Date::create(2017, 4, 16)],
            [Date::create(2018, 4, 1)],
            [Date::create(2030, 4, 21)],
        ];
    }
}
