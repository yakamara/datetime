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
use Yakamara\DateTime\Range\DateTimeRange;

final class DateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromDateTime()
    {
        $input = new \DateTime();
        $date = Date::createFromDateTime($input);

        $this->assertInstanceOf(Date::class, $date);
        $this->assertEquals($input->format('Y-m-d'), $date->formatIso());

        $input = new DateTime();
        $date = Date::createFromDateTime($input);

        $this->assertInstanceOf(Date::class, $date);
        $this->assertSame($input->formatIsoDate(), $date->formatIso());

        $input = new Date();
        $date = Date::createFromDateTime($input);

        $this->assertSame($input, $date);
    }

    public function testCreateFromTimestamp()
    {
        $input = time();
        $date = Date::createFromTimestamp($input);

        $this->assertInstanceOf(Date::class, $date);
        $this->assertSame(date('Y-m-d', $input), $date->formatIso());
    }

    public function testConstruct()
    {
        $date = new Date();

        $this->assertSame(date('Y-m-d'), $date->formatIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('2016-09-08');

        $this->assertSame('2016-09-08', $date->formatIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('2016-09-08 22:07:02');

        $this->assertSame('2016-09-08', $date->formatIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('@'.strtotime('2016-09-08 22:07:02'));

        $this->assertSame('2016-09-08', $date->formatIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));
    }

    public function testCreate()
    {
        $date = Date::create(2016, 9, 8);

        $this->assertSame('2016-09-08', $date->formatIso());
    }

    public function testToday()
    {
        $today = Date::today();

        $this->assertSame(Date::today(), $today);
        $this->assertEquals(strtotime('today'), $today->getTimestamp());
    }

    public function testYesterday()
    {
        $yesterday = Date::yesterday();

        $this->assertSame(Date::yesterday(), $yesterday);
        $this->assertEquals(strtotime('yesterday'), $yesterday->getTimestamp());
    }

    public function testTomorrow()
    {
        $tomorrow = Date::tomorrow();

        $this->assertSame(Date::tomorrow(), $tomorrow);
        $this->assertEquals(strtotime('tomorrow'), $tomorrow->getTimestamp());
    }

    public function testFormatIso()
    {
        $input = '2016-09-08';

        $this->assertSame($input, (new Date($input))->formatIso());
    }

    public function testToDateTime()
    {
        $date = new Date();
        $dateTime = $date->toDateTime();

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame($date->formatIso(), $dateTime->formatIsoDate());
        $this->assertSame('00:00:00', $dateTime->formatIsoTime());
    }

    public function testToRange()
    {
        $date = new Date('2016-09-11');
        $range = $date->toRange();

        $this->assertInstanceOf(DateTimeRange::class, $range);
        $this->assertSame('2016-09-11 00:00:00', $range->getStart()->formatIso());
        $this->assertSame('2016-09-12 00:00:00', $range->getEnd()->formatIso());
    }

    public function testToUtcRange()
    {
        $date = new Date('2016-09-11');
        $range = $date->toUtcRange();

        $this->assertInstanceOf(DateTimeRange::class, $range);
        $this->assertSame('2016-09-10 22:00:00', $range->getStart()->formatIso());
        $this->assertSame('2016-09-11 22:00:00', $range->getEnd()->formatIso());
    }

    /**
     * @dataProvider provideIsStartOfYear
     */
    public function testIsStartOfYear(bool $expected, string $date)
    {
        $this->assertSame($expected, (new Date($date))->isStartOfYear());
    }

    public function provideIsStartOfYear(): array
    {
        return [
            [true, '2016-01-01'],
            [true, '2017-01-01'],
            [false, '2016-12-31'],
            [false, '2016-01-02'],
            [false, '2016-05-01'],
        ];
    }

    /**
     * @dataProvider provideIsEndOfYear
     */
    public function testIsEndOfYear(bool $expected, string $date)
    {
        $this->assertSame($expected, (new Date($date))->isEndOfYear());
    }

    public function provideIsEndOfYear(): array
    {
        return [
            [true, '2016-12-31'],
            [true, '2017-12-31'],
            [false, '2016-01-01'],
            [false, '2016-05-31'],
        ];
    }

    /**
     * @dataProvider provideIsStartOfMonth
     */
    public function testIsStartOfMonth(bool $expected, string $date)
    {
        $this->assertSame($expected, (new Date($date))->isStartOfMonth());
    }

    public function provideIsStartOfMonth(): array
    {
        return [
            [true, '2016-01-01'],
            [true, '2016-05-01'],
            [false, '2016-02-29'],
            [false, '2016-01-02'],
        ];
    }

    /**
     * @dataProvider provideIsEndOfMonth
     */
    public function testIsEndOfMonth(bool $expected, string $date)
    {
        $this->assertSame($expected, (new Date($date))->isEndOfMonth());
    }

    public function provideIsEndOfMonth(): array
    {
        return [
            [true, '2016-01-31'],
            [true, '2016-02-29'],
            [false, '2016-03-01'],
            [false, '2016-05-30'],
        ];
    }
}
