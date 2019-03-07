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

use PHPUnit\Framework\TestCase;
use Yakamara\DateTime\Date;
use Yakamara\DateTime\DateTime;
use Yakamara\DateTime\Range\DateTimeRange;

final class DateTest extends TestCase
{
    public function testCreateFromDateTime(): void
    {
        $input = new \DateTime();
        $date = Date::createFromDateTime($input);

        self::assertInstanceOf(Date::class, $date);
        self::assertEquals($input->format('Y-m-d'), $date->formatIso());

        $input = new DateTime();
        $date = Date::createFromDateTime($input);

        self::assertInstanceOf(Date::class, $date);
        self::assertSame($input->formatIsoDate(), $date->formatIso());

        $input = new Date();
        $date = Date::createFromDateTime($input);

        self::assertSame($input, $date);
    }

    public function testCreateFromTimestamp(): void
    {
        $input = time();
        $date = Date::createFromTimestamp($input);

        self::assertInstanceOf(Date::class, $date);
        self::assertSame(date('Y-m-d', $input), $date->formatIso());
    }

    public function testCreateFromFormat(): void
    {
        $date = Date::createFromFormat('d.m.Y His', '12.09.2016 151800');

        self::assertInstanceOf(Date::class, $date);
        self::assertSame('2016-09-12', $date->formatIso());
        self::assertSame('00:00:00', $date->format('H:i:s'));
    }

    public function testConstruct(): void
    {
        $date = new Date();

        self::assertSame(date('Y-m-d'), $date->formatIso());
        self::assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('2016-09-08');

        self::assertSame('2016-09-08', $date->formatIso());
        self::assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('2016-09-08 22:07:02');

        self::assertSame('2016-09-08', $date->formatIso());
        self::assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('@'.strtotime('2016-09-08 22:07:02'));

        self::assertSame('2016-09-08', $date->formatIso());
        self::assertSame('00:00:00', $date->format('H:i:s'));
    }

    public function testCreate(): void
    {
        $date = Date::create(2016, 9, 8);

        self::assertSame('2016-09-08', $date->formatIso());
    }

    public function testToday(): void
    {
        $today = Date::today();

        self::assertSame(Date::today(), $today);
        self::assertEquals(strtotime('today'), $today->getTimestamp());
    }

    public function testYesterday(): void
    {
        $yesterday = Date::yesterday();

        self::assertSame(Date::yesterday(), $yesterday);
        self::assertEquals(strtotime('yesterday'), $yesterday->getTimestamp());
    }

    public function testTomorrow(): void
    {
        $tomorrow = Date::tomorrow();

        self::assertSame(Date::tomorrow(), $tomorrow);
        self::assertEquals(strtotime('tomorrow'), $tomorrow->getTimestamp());
    }

    public function testFormatIso(): void
    {
        $input = '2016-09-08';

        self::assertSame($input, (new Date($input))->formatIso());
    }

    public function testFormatIntl(): void
    {
        $date = new Date('2016-09-08');

        self::assertSame('8. September 2016', $date->formatIntl());
        self::assertSame('08.09.16', $date->formatIntl(\IntlDateFormatter::SHORT));
    }

    public function testToMutable(): void
    {
        $date = new Date('2016-09-08');
        $mutable = $date->toMutable();

        self::assertInstanceOf(\DateTime::class, $mutable);
        self::assertSame($date->getTimestamp(), $mutable->getTimestamp());
    }

    public function testToDateTime(): void
    {
        $date = new Date();
        $dateTime = $date->toDateTime();

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame($date->formatIso(), $dateTime->formatIsoDate());
        self::assertSame('00:00:00', $dateTime->formatIsoTime());
    }

    public function testToRange(): void
    {
        $date = new Date('2016-09-11');
        $range = $date->toRange();

        self::assertInstanceOf(DateTimeRange::class, $range);
        self::assertSame('2016-09-11 00:00:00', $range->getStart()->formatIso());
        self::assertSame('2016-09-12 00:00:00', $range->getEnd()->formatIso());
    }

    public function testToUtcRange(): void
    {
        $date = new Date('2016-09-11');
        $range = $date->toUtcRange();

        self::assertInstanceOf(DateTimeRange::class, $range);
        self::assertSame('2016-09-10 22:00:00', $range->getStart()->formatIso());
        self::assertSame('2016-09-11 22:00:00', $range->getEnd()->formatIso());
    }

    /**
     * @dataProvider provideIsStartOfYear
     */
    public function testIsStartOfYear(bool $expected, string $date): void
    {
        self::assertSame($expected, (new Date($date))->isStartOfYear());
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
    public function testIsEndOfYear(bool $expected, string $date): void
    {
        self::assertSame($expected, (new Date($date))->isEndOfYear());
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
    public function testIsStartOfMonth(bool $expected, string $date): void
    {
        self::assertSame($expected, (new Date($date))->isStartOfMonth());
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
    public function testIsEndOfMonth(bool $expected, string $date): void
    {
        self::assertSame($expected, (new Date($date))->isEndOfMonth());
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
