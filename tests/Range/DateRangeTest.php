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

final class DateRangeTest extends \PHPUnit_Framework_TestCase
{
    public function testToDateTimeRange()
    {
        $dateRange = new DateRange(new Date('2016-09-08'), new Date('2016-09-11'));
        $dateTimeRange = $dateRange->toDateTimeRange();

        $this->assertInstanceOf(DateTimeRange::class, $dateTimeRange);
        $this->assertSame('2016-09-08 00:00:00', $dateTimeRange->getStart()->formatIso());
        $this->assertSame('2016-09-12 00:00:00', $dateTimeRange->getEnd()->formatIso());
    }

    public function testToUtcDateTimeRange()
    {
        $dateRange = new DateRange(new Date('2016-09-08'), new Date('2016-09-11'));
        $dateTimeRange = $dateRange->toUtcDateTimeRange();

        $this->assertInstanceOf(DateTimeRange::class, $dateTimeRange);
        $this->assertSame('2016-09-07 22:00:00', $dateTimeRange->getStart()->formatIso());
        $this->assertSame('2016-09-11 22:00:00', $dateTimeRange->getEnd()->formatIso());
    }

    /**
     * @dataProvider provideIsSameYear
     */
    public function testIsSameYear(bool $expected, string $start, string $end)
    {
        $dateRange = new DateRange(new Date($start), new Date($end));

        $this->assertSame($expected, $dateRange->isSameYear());
    }

    public function provideIsSameYear()
    {
        return [
            [true, '2016-01-01', '2016-12-31'],
            [true, '2016-05-01', '2016-05-01'],
            [false, '2016-01-01', '2017-01-01'],
            [false, '2016-12-31', '2017-01-01'],
        ];
    }

    /**
     * @dataProvider provideIsSameMonth
     */
    public function testIsSameMonth(bool $expected, string $start, string $end)
    {
        $dateRange = new DateRange(new Date($start), new Date($end));

        $this->assertSame($expected, $dateRange->isSameMonth());
    }

    public function provideIsSameMonth()
    {
        return [
            [true, '2016-01-01', '2016-01-31'],
            [true, '2016-05-03', '2016-05-05'],
            [false, '2016-05-03', '2017-05-05'],
            [false, '2016-05-31', '2017-06-01'],
            [false, '2016-05-31', '2017-06-01'],
        ];
    }

    /**
     * @dataProvider provideIsSameDay
     */
    public function testIsSameDay(bool $expected, string $start, string $end)
    {
        $dateRange = new DateRange(new Date($start), new Date($end));

        $this->assertSame($expected, $dateRange->isSameDay());
    }

    public function provideIsSameDay()
    {
        return [
            [true, '2016-09-11', '2016-09-11'],
            [false, '2016-09-11', '2017-09-11'],
            [false, '2016-09-11', '2016-10-11'],
            [false, '2016-09-11', '2016-09-12'],
        ];
    }

    /**
     * @dataProvider provideIsWholeYear
     */
    public function testIsWholeYear(bool $expected, string $start, string $end)
    {
        $dateRange = new DateRange(new Date($start), new Date($end));

        $this->assertSame($expected, $dateRange->isWholeYear());
    }

    public function provideIsWholeYear()
    {
        return [
            [true, '2016-01-01', '2016-12-31'],
            [false, '2016-01-01', '2017-01-01'],
            [false, '2016-01-01', '2017-12-31'],
            [false, '2016-02-01', '2016-12-31'],
        ];
    }

    /**
     * @dataProvider provideIsWholeMonth
     */
    public function testIsWholeMonth(bool $expected, string $start, string $end)
    {
        $dateRange = new DateRange(new Date($start), new Date($end));

        $this->assertSame($expected, $dateRange->isWholeMonth());
    }

    public function provideIsWholeMonth()
    {
        return [
            [true, '2016-01-01', '2016-01-31'],
            [true, '2016-02-01', '2016-02-29'],
            [false, '2016-01-01', '2016-02-29'],
            [false, '2016-01-02', '2016-01-31'],
            [false, '2016-01-01', '2016-01-30'],
        ];
    }

    /**
     * @dataProvider provideIsWholeDay
     */
    public function testIsWholeDay(bool $expected, string $start, string $end)
    {
        $dateRange = new DateRange(new Date($start), new Date($end));

        $this->assertSame($expected, $dateRange->isWholeDay());
    }

    public function provideIsWholeDay()
    {
        return [
            [true, '2016-09-11', '2016-09-11'],
            [false, '2016-09-11', '2017-09-11'],
            [false, '2016-09-11', '2016-10-11'],
            [false, '2016-09-11', '2016-09-12'],
        ];
    }

    /**
     * @dataProvider provideContains
     */
    public function testContains(bool $expected, DateTimeInterface $contains)
    {
        $dateRange = new DateRange(new Date('2016-08-03'), new Date('2016-09-11'));

        $this->assertSame($expected, $dateRange->contains($contains));
    }

    public function provideContains()
    {
        return [
            [true, new Date('2016-08-03')],
            [true, new Date('2016-08-25')],
            [true, new Date('2016-09-11')],
            [false, new Date('2016-08-02')],
            [false, new Date('2016-09-12')],
            [false, new Date('2017-08-10')],
            [true, new DateTime('2016-08-03 00:00:00')],
            [true, new DateTime('2016-08-03 15:23:00')],
            [true, new DateTime('2016-09-11 23:59:59')],
            [false, new DateTime('2016-08-02 23:59:59')],
            [false, new DateTime('2016-09-12 00:00:00')],
        ];
    }
}
