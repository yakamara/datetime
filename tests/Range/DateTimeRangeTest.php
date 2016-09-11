<?php declare(strict_types=1);

/*
 * This file is part of the datetime package.
 *
 * (c) Yakamara Media GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakamara\DateTime\Tests;

use Yakamara\DateTime\Date;
use Yakamara\DateTime\DateTime;
use Yakamara\DateTime\DateTimeInterface;
use Yakamara\DateTime\Range\DateRange;
use Yakamara\DateTime\Range\DateTimeRange;

final class DateTimeRangeTest extends \PHPUnit_Framework_TestCase
{
    public function testToUtc()
    {
        $dateTimeRange = new DateTimeRange(new DateTime('2016-09-08 13:15:00'), new DateTime('2016-09-11 04:00:12'));
        $dateTimeRange = $dateTimeRange->toUtc();

        $this->assertInstanceOf(DateTimeRange::class, $dateTimeRange);
        $this->assertSame('2016-09-08 11:15:00', $dateTimeRange->getStart()->formatIso());
        $this->assertSame('2016-09-11 02:00:12', $dateTimeRange->getEnd()->formatIso());
    }

    public function testToDateRange()
    {
        $dateTimeRange = new DateTimeRange(new DateTime('2016-09-08 13:15:00'), new DateTime('2016-09-11 04:00:12'));
        $dateRange = $dateTimeRange->toDateRange();

        $this->assertInstanceOf(DateRange::class, $dateRange);
        $this->assertSame('2016-09-08 00:00:00', $dateRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2016-09-11 00:00:00', $dateRange->getEnd()->format('Y-m-d H:i:s'));

        $dateTimeRange = new DateTimeRange(new DateTime('2016-09-08 13:15:00'), new DateTime('2016-09-11 00:00:00'));
        $dateRange = $dateTimeRange->toDateRange();

        $this->assertSame('2016-09-10 00:00:00', $dateRange->getEnd()->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider provideIsSameYear
     */
    public function testIsSameYear(bool $expected, string $start, string $end)
    {
        $dateTimeRange = new DateTimeRange(new DateTime($start), new DateTime($end));

        $this->assertSame($expected, $dateTimeRange->isSameYear());
    }

    public function provideIsSameYear()
    {
        return [
            [true, '2016-01-01 00:00:00', '2016-12-31 23:59:59'],
            [true, '2016-05-01 12:13:14', '2016-05-01 12:13:14'],
            [false, '2016-01-01 00:00:00', '2017-01-01 00:00:00'],
            [false, '2016-12-31 23:59:59', '2017-01-01 00:00:00'],
        ];
    }

    /**
     * @dataProvider provideIsSameMonth
     */
    public function testIsSameMonth(bool $expected, string $start, string $end)
    {
        $dateTimeRange = new DateTimeRange(new DateTime($start), new DateTime($end));

        $this->assertSame($expected, $dateTimeRange->isSameMonth());
    }

    public function provideIsSameMonth()
    {
        return [
            [true, '2016-01-01 00:00:00', '2016-01-31 23:59:59'],
            [true, '2016-05-03 13:00:00', '2016-05-05 02:00:00'],
            [false, '2016-05-03 00:00:00', '2017-05-05 00:00:00'],
            [false, '2016-05-31 23:59:59', '2017-06-01 00:00:00'],
            [false, '2016-05-31', '2017-06-01'],
        ];
    }

    /**
     * @dataProvider provideIsSameDay
     */
    public function testIsSameDay(bool $expected, string $start, string $end)
    {
        $dateTimeRange = new DateTimeRange(new DateTime($start), new DateTime($end));

        $this->assertSame($expected, $dateTimeRange->isSameDay());
    }

    public function provideIsSameDay()
    {
        return [
            [true, '2016-09-11 00:00:00', '2016-09-11 23:59:59'],
            [true, '2016-09-11 12:13:00', '2016-09-11 13:00:30'],
            [false, '2016-09-11 00:00:00', '2017-09-11 00:00:00'],
            [false, '2016-09-11 00:00:00', '2016-10-11 00:00:00'],
            [false, '2016-09-11 23:59:59', '2016-09-12 00:00:00'],
        ];
    }

    /**
     * @dataProvider provideIsWholeYear
     */
    public function testIsWholeYear(bool $expected, string $start, string $end)
    {
        $dateTimeRange = new DateTimeRange(new DateTime($start), new DateTime($end));

        $this->assertSame($expected, $dateTimeRange->isWholeYear());
    }

    public function provideIsWholeYear()
    {
        return [
            [true, '2016-01-01 00:00:00', '2017-01-01 00:00:00'],
            [false, '2016-01-01 00:00:00', '2016-12-31 23:59:59'],
            [false, '2016-01-01 00:00:01', '2017-01-01 00:00:00'],
            [false, '2016-01-01 00:00:00', '2017-01-02 00:00:00'],
        ];
    }

    /**
     * @dataProvider provideIsWholeMonth
     */
    public function testIsWholeMonth(bool $expected, string $start, string $end)
    {
        $dateTimeRange = new DateTimeRange(new DateTime($start), new DateTime($end));

        $this->assertSame($expected, $dateTimeRange->isWholeMonth());
    }

    public function provideIsWholeMonth()
    {
        return [
            [true, '2016-01-01 00:00:00', '2016-02-01 00:00:00'],
            [true, '2016-02-01 00:00:00', '2016-03-01 00:00:00'],
            [false, '2016-02-01 00:00:00', '2016-02-29 23:59:59'],
            [false, '2016-01-01 00:00:00', '2016-01-31 00:00:00'],
        ];
    }

    /**
     * @dataProvider provideIsWholeDay
     */
    public function testIsWholeDay(bool $expected, string $start, string $end)
    {
        $dateTimeRange = new DateTimeRange(new DateTime($start), new DateTime($end));

        $this->assertSame($expected, $dateTimeRange->isWholeDay());
    }

    public function provideIsWholeDay()
    {
        return [
            [true, '2016-09-11 00:00:00', '2016-09-12 00:00:00'],
            [false, '2016-09-11 00:00:00', '2016-09-11 23:59:59'],
            [false, '2016-09-11 00:00:01', '2016-09-12 00:00:00'],
            [false, '2016-09-11 00:00:00', '2016-09-12 00:00:01'],
            [false, '2016-09-11 00:00:00', '2017-09-12 00:00:00'],
        ];
    }

    /**
     * @dataProvider provideContains
     */
    public function testContains(bool $expected, DateTimeInterface $contains)
    {
        $dateTimeRange = new DateTimeRange(new DateTime('2016-09-08 13:15:00'), new DateTime('2016-09-11 04:00:12'));

        $this->assertSame($expected, $dateTimeRange->contains($contains));
    }

    public function provideContains()
    {
        return [
            [true, new DateTime('2016-09-08 13:15:00')],
            [true, new DateTime('2016-09-11 04:00:11')],
            [true, new DateTime('2016-09-10 00:00:00')],
            [false, new DateTime('2016-09-08 13:14:59')],
            [false, new DateTime('2016-09-11 04:00:12')],
            [false, new DateTime('2016-09-07 00:00:00')],
            [false, new DateTime('2016-09-12 00:00:00')],
            [true, new Date('2016-09-08')],
            [true, new Date('2016-09-11')],
            [false, new Date('2016-09-07')],
            [false, new Date('2016-09-12')],
        ];
    }
}
