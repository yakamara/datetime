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

final class DateTimeTest extends TestCase
{
    public function testCreateFromDateTime(): void
    {
        $input = new \DateTime();
        $dateTime = DateTime::createFromDateTime($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertEquals($input, $dateTime);

        $input = new DateTime();
        $dateTime = DateTime::createFromDateTime($input);

        self::assertSame($input, $dateTime);

        $input = new Date();
        $dateTime = DateTime::createFromDateTime($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame($input->formatIso(), $dateTime->formatIsoDate());
        self::assertSame('00:00:00', $dateTime->formatIsoTime());
    }

    public function testCreateFromTimestamp(): void
    {
        $input = time();
        $dateTime = DateTime::createFromTimestamp($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame($input, $dateTime->getTimestamp());
    }

    public function testCreateFromFormat(): void
    {
        $dateTime = DateTime::createFromFormat('d.m.Y His', '12.09.2016 151800');

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame('2016-09-12 15:18:00', $dateTime->formatIso());
    }

    public function testCreate(): void
    {
        $dateTime = DateTime::create(2016, 9, 8);

        self::assertSame('2016-09-08 00:00:00', $dateTime->formatIso());

        $dateTime = DateTime::create(2016, 9, 8, 22, 7, 2);

        self::assertSame('2016-09-08 22:07:02', $dateTime->formatIso());
    }

    public function testCreateUtc(): void
    {
        $dateTime = DateTime::createUtc('2016-09-08 22:07:02');

        self::assertEquals(DateTime::getUtcTimezone(), $dateTime->getTimezone());
        self::assertSame('2016-09-08 22:07:02', $dateTime->formatIso());
    }

    public function testCreateFromUtc(): void
    {
        $dateTime = DateTime::createFromUtc('2016-09-08 22:07:02');

        self::assertEquals(DateTime::getDefaultTimezone(), $dateTime->getTimezone());
        self::assertSame('2016-09-09 00:07:02', $dateTime->formatIso());
    }

    public function testNow(): void
    {
        $preNow = time();
        $now = DateTime::now();
        $postNow = time();

        self::assertGreaterThanOrEqual($preNow, $now->getTimestamp());
        self::assertLessThanOrEqual($postNow, $now->getTimestamp());
    }

    public function testFormatIso(): void
    {
        $input = '2016-09-08 22:07:02';

        self::assertSame($input, (new DateTime($input))->formatIso());
    }

    public function testFormatIsoDate(): void
    {
        self::assertSame('2016-09-08', (new DateTime('2016-09-08 22:07:02'))->formatIsoDate());
    }

    public function testFormatIsoTime(): void
    {
        self::assertSame('22:07:02', (new DateTime('2016-09-08 22:07:02'))->formatIsoTime());
    }

    public function testFormatIntl(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $string = $dateTime->formatIntl();
        self::assertStringStartsWith('8. September 2016', $string);
        self::assertStringEndsWith('22:07:02 MESZ', $string);

        $string = $dateTime->formatIntl(\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT);
        self::assertStringStartsWith('08.09.2016', $string);
        self::assertStringEndsWith('22:07', $string);

        $string = $dateTime->formatIntl(\IntlDateFormatter::SHORT);
        self::assertStringStartsWith('08.09.16', $string);
        self::assertStringEndsWith('22:07', $string);
    }

    public function testFormatIntlDate(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame('8. September 2016', $dateTime->formatIntlDate());
        self::assertSame('08.09.16', $dateTime->formatIntlDate(\IntlDateFormatter::SHORT));
    }

    public function testFormatIntlTime(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame('22:07:02 MESZ', $dateTime->formatIntlTime());
        self::assertSame('22:07', $dateTime->formatIntlTime(\IntlDateFormatter::SHORT));
    }

    public function testToMutable(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');
        $mutable = $dateTime->toMutable();

        self::assertInstanceOf(\DateTime::class, $mutable);
        self::assertEquals($dateTime, $mutable);
    }

    public function testToDate(): void
    {
        $dateTime = new DateTime();
        $date = $dateTime->toDate();

        self::assertInstanceOf(Date::class, $date);
        self::assertSame($dateTime->formatIsoDate(), $date->formatIso());
    }

    public function testToUtc(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame('2016-09-08 20:07:02', $dateTime->toUtc()->formatIso());
    }

    public function testGetHour(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame(22, $dateTime->getHour());
    }

    public function testGetMinute(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame(7, $dateTime->getMinute());
    }

    public function testGetSecond(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame(2, $dateTime->getSecond());
    }

    /**
     * @dataProvider provideIsStartOfYear
     */
    public function testIsStartOfYear(bool $expected, string $dateTime): void
    {
        self::assertSame($expected, (new DateTime($dateTime))->isStartOfYear());
    }

    public function provideIsStartOfYear(): array
    {
        return [
            [true, '2016-01-01 00:00:00'],
            [true, '2017-01-01 00:00:00'],
            [false, '2016-12-31 00:00:00'],
            [false, '2016-01-02 00:00:00'],
            [false, '2016-05-01 00:00:00'],
            [false, '2016-01-01 00:00:01'],
            [false, '2016-01-01 02:00:00'],
        ];
    }

    /**
     * @dataProvider provideIsStartOfMonth
     */
    public function testIsStartOfMonth(bool $expected, string $dateTime): void
    {
        self::assertSame($expected, (new DateTime($dateTime))->isStartOfMonth());
    }

    public function provideIsStartOfMonth(): array
    {
        return [
            [true, '2016-01-01 00:00:00'],
            [true, '2016-05-01 00:00:00'],
            [false, '2016-02-29 00:00:00'],
            [false, '2016-01-02 00:00:00'],
            [false, '2016-01-01 00:00:01'],
            [false, '2016-01-01 02:00:00'],
        ];
    }

    /**
     * @dataProvider provideIsMidnight
     */
    public function testIsMidnight(bool $expected, string $dateTime): void
    {
        self::assertSame($expected, (new DateTime($dateTime))->isMidnight());
    }

    public function provideIsMidnight(): array
    {
        return [
            [true, '2016-09-11 00:00:00'],
            [false, '2016-09-11 23:59:59'],
            [false, '2016-09-11 12:00:00'],
        ];
    }

    public function testAddHours(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame('2016-09-09 00:07:02', $dateTime->addHours(2)->formatIso());
        self::assertSame('2016-09-08 19:07:02', $dateTime->addHours(-3)->formatIso());
    }

    public function testAddMinutes(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame('2016-09-08 22:09:02', $dateTime->addMinutes(2)->formatIso());
        self::assertSame('2016-09-08 22:04:02', $dateTime->addMinutes(-3)->formatIso());
    }

    public function testAddSeconds(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        self::assertSame('2016-09-08 22:07:04', $dateTime->addSeconds(2)->formatIso());
        self::assertSame('2016-09-08 22:06:59', $dateTime->addSeconds(-3)->formatIso());
    }
}
