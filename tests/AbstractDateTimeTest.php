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
use Yakamara\DateTime\AbstractDateTime;
use Yakamara\DateTime\Date;
use Yakamara\DateTime\DateTime;
use Yakamara\DateTime\DateTimeInterface;

final class AbstractDateTimeTest extends TestCase
{
    public function testCreateFromDateTime(): void
    {
        $input = new \DateTime();
        $dateTime = AbstractDateTime::createFromDateTime($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertEquals($input, $dateTime);

        $input = new DateTime();
        $dateTime = AbstractDateTime::createFromDateTime($input);

        self::assertSame($input, $dateTime);

        $input = new Date();
        $dateTime = AbstractDateTime::createFromDateTime($input);

        self::assertSame($input, $dateTime);
    }

    public function testCreateFromTimestamp(): void
    {
        $input = time();
        $dateTime = AbstractDateTime::createFromTimestamp($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame($input, $dateTime->getTimestamp());
    }

    public function testCreateFromFormat(): void
    {
        $dateTime = AbstractDateTime::createFromFormat('d.m.Y His', '12.09.2016 151800');

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame('2016-09-12 15:18:00', $dateTime->formatIso());
    }

    public function testCreateFromUnknown(): void
    {
        $input = time();
        $dateTime = AbstractDateTime::createFromUnknown($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame($input, $dateTime->getTimestamp());

        $input = new \DateTime();
        $dateTime = AbstractDateTime::createFromUnknown($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertEquals($input, $dateTime);

        $input = '2016-09-08 22:07:00';
        $dateTime = AbstractDateTime::createFromUnknown($input);

        self::assertInstanceOf(DateTime::class, $dateTime);
        self::assertSame($input, $dateTime->format('Y-m-d H:i:s'));
    }

    public function testFormatLocalized(): void
    {
        $input = '2016-09-08 22:07:00';
        $dateTime = new DateTime($input);

        self::assertSame($input, $dateTime->formatLocalized('%Y-%m-%d %H:%M:%S'));
    }

    public function testGetYear(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame(2016, $dateTime->getYear());
    }

    public function testGetMonth(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame(9, $dateTime->getMonth());
    }

    public function testGetDay(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame(8, $dateTime->getDay());
    }

    public function testGetWeekday(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame(4, $dateTime->getWeekday());
    }

    public function testAddYears(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame('2018-09-08 22:07:00', $dateTime->addYears(2)->formatIso());
        self::assertSame('2013-09-08 22:07:00', $dateTime->addYears(-3)->formatIso());
    }

    public function testAddMonths(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame('2016-11-08 22:07:00', $dateTime->addMonths(2)->formatIso());
        self::assertSame('2016-06-08 22:07:00', $dateTime->addMonths(-3)->formatIso());
    }

    public function testAddWeeks(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame('2016-09-22 22:07:00', $dateTime->addWeeks(2)->formatIso());
        self::assertSame('2016-08-18 22:07:00', $dateTime->addWeeks(-3)->formatIso());
    }

    public function testAddDays(): void
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        self::assertSame('2016-09-10 22:07:00', $dateTime->addDays(2)->formatIso());
        self::assertSame('2016-09-05 22:07:00', $dateTime->addDays(-3)->formatIso());
    }

    /**
     * @dataProvider provideWorkdayDifference
     */
    public function testAddWorkdays(DateTimeInterface $input, DateTimeInterface $expected, int $days): void
    {
        $this->assertEquals($expected, $input->addWorkdays($days));
    }

    /**
     * @dataProvider provideWorkdayDifference
     */
    public function testDiffWorkdays(DateTimeInterface $input, DateTimeInterface $dateTime2, int $expected): void
    {
        $this->assertEquals($expected, $input->diffWorkdays($dateTime2));
    }

    public function provideWorkdayDifference()
    {
        return [
            [new Date('2016-09-08'), new Date('2016-09-08'), 0],
            [new Date('2016-09-05'), new Date('2016-09-08'), 3],
            [new Date('2016-09-07'), new Date('2016-09-05'), -2],
            [new Date('2016-09-08'), new Date('2016-09-12'), 2],
            [new Date('2016-09-10'), new Date('2016-09-10'), 0],
            [new Date('2016-09-10'), new Date('2016-09-12'), 1],
            [new Date('2017-04-12'), new Date('2017-04-19'), 3],
            [new DateTime('2016-09-08 22:07:02'), new DateTime('2016-09-22 22:07:02'), 10],
        ];
    }
}
