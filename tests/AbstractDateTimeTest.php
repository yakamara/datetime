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

use Yakamara\DateTime\AbstractDateTime;
use Yakamara\DateTime\Date;
use Yakamara\DateTime\DateTime;
use Yakamara\DateTime\DateTimeInterface;

final class AbstractDateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromDateTime()
    {
        $input = new \DateTime();
        $dateTime = AbstractDateTime::createFromDateTime($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertEquals($input, $dateTime);

        $input = new DateTime();
        $dateTime = AbstractDateTime::createFromDateTime($input);

        $this->assertSame($input, $dateTime);

        $input = new Date();
        $dateTime = AbstractDateTime::createFromDateTime($input);

        $this->assertSame($input, $dateTime);
    }

    public function testCreateFromTimestamp()
    {
        $input = time();
        $dateTime = AbstractDateTime::createFromTimestamp($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame($input, $dateTime->getTimestamp());
    }

    public function testCreateFromUnknown()
    {
        $input = time();
        $dateTime = AbstractDateTime::createFromUnknown($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame($input, $dateTime->getTimestamp());

        $input = new \DateTime();
        $dateTime = AbstractDateTime::createFromUnknown($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertEquals($input, $dateTime);

        $input = '2016-09-08 22:07:00';
        $dateTime = AbstractDateTime::createFromUnknown($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame($input, $dateTime->format('Y-m-d H:i:s'));
    }

    public function testFormatLocalized()
    {
        $input = '2016-09-08 22:07:00';
        $dateTime = new DateTime($input);

        $this->assertSame($input, $dateTime->formatLocalized('%Y-%m-%d %H:%M:%S'));
    }

    public function testGetYear()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame(2016, $dateTime->getYear());
    }

    public function testGetMonth()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame(9, $dateTime->getMonth());
    }

    public function testGetDay()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame(8, $dateTime->getDay());
    }

    public function testGetWeekday()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame(4, $dateTime->getWeekday());
    }

    public function testAddYears()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame('2018-09-08 22:07:00', $dateTime->addYears(2)->toIso());
        $this->assertSame('2013-09-08 22:07:00', $dateTime->addYears(-3)->toIso());
    }

    public function testAddMonths()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame('2016-11-08 22:07:00', $dateTime->addMonths(2)->toIso());
        $this->assertSame('2016-06-08 22:07:00', $dateTime->addMonths(-3)->toIso());
    }

    public function testAddWeeks()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame('2016-09-22 22:07:00', $dateTime->addWeeks(2)->toIso());
        $this->assertSame('2016-08-18 22:07:00', $dateTime->addWeeks(-3)->toIso());
    }

    public function testAddDays()
    {
        $dateTime = new DateTime('2016-09-08 22:07:00');

        $this->assertSame('2016-09-10 22:07:00', $dateTime->addDays(2)->toIso());
        $this->assertSame('2016-09-05 22:07:00', $dateTime->addDays(-3)->toIso());
    }

    /**
     * @dataProvider provideWorkdayDifference
     */
    public function testAddWorkdays(DateTimeInterface $input, DateTimeInterface $expected, int $days)
    {
        $this->assertEquals($expected, $input->addWorkdays($days));
    }

    /**
     * @dataProvider provideWorkdayDifference
     */
    public function testDiffWorkdays(DateTimeInterface $input, DateTimeInterface $dateTime2, int $expected)
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
