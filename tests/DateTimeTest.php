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

final class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromDateTime()
    {
        $input = new \DateTime();
        $dateTime = DateTime::createFromDateTime($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertEquals($input, $dateTime);

        $input = new DateTime();
        $dateTime = DateTime::createFromDateTime($input);

        $this->assertSame($input, $dateTime);

        $input = new Date();
        $dateTime = DateTime::createFromDateTime($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame($input->toIso(), $dateTime->toIsoDate());
        $this->assertSame('00:00:00', $dateTime->toIsoTime());
    }

    public function testCreateFromTimestamp()
    {
        $input = time();
        $dateTime = DateTime::createFromTimestamp($input);

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame($input, $dateTime->getTimestamp());
    }

    public function testCreate()
    {
        $dateTime = DateTime::create(2016, 9, 8);

        $this->assertSame('2016-09-08 00:00:00', $dateTime->toIso());

        $dateTime = DateTime::create(2016, 9, 8, 22, 7, 2);

        $this->assertSame('2016-09-08 22:07:02', $dateTime->toIso());
    }

    public function testCreateFromUtc()
    {
        $dateTime = DateTime::createFromUtc('2016-09-08 22:07:02');

        $this->assertSame('2016-09-09 00:07:02', $dateTime->toIso());
    }

    public function testNow()
    {
        $preNow = time();
        $now = DateTime::now();
        $postNow = time();

        $this->assertGreaterThanOrEqual($preNow, $now->getTimestamp());
        $this->assertLessThanOrEqual($postNow, $now->getTimestamp());
    }

    public function testToIso()
    {
        $input = '2016-09-08 22:07:02';

        $this->assertSame($input, (new DateTime($input))->toIso());
    }

    public function testToIsoDate()
    {
        $this->assertSame('2016-09-08', (new DateTime('2016-09-08 22:07:02'))->toIsoDate());
    }

    public function testToIsoTime()
    {
        $this->assertSame('22:07:02', (new DateTime('2016-09-08 22:07:02'))->toIsoTime());
    }

    public function toDate()
    {
        $dateTime = new DateTime();
        $date = $dateTime->toDate();

        $this->assertInstanceOf(Date::class, $date);
        $this->assertSame($dateTime->toIsoDate(), $date->toIso());
    }

    public function toUtc()
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $this->assertSame('2016-09-08 20:07:02', $dateTime->toUtc()->toIso());
    }

    public function testGetHour()
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $this->assertSame(22, $dateTime->getHour());
    }

    public function testGetMinute()
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $this->assertSame(7, $dateTime->getMinute());
    }

    public function testGetSecond()
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $this->assertSame(2, $dateTime->getSecond());
    }

    public function testAddHours()
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $this->assertSame('2016-09-09 00:07:02', $dateTime->addHours(2)->toIso());
        $this->assertSame('2016-09-08 19:07:02', $dateTime->addHours(-3)->toIso());
    }

    public function testAddMinutes()
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $this->assertSame('2016-09-08 22:09:02', $dateTime->addMinutes(2)->toIso());
        $this->assertSame('2016-09-08 22:04:02', $dateTime->addMinutes(-3)->toIso());
    }

    public function testAddSeconds()
    {
        $dateTime = new DateTime('2016-09-08 22:07:02');

        $this->assertSame('2016-09-08 22:07:04', $dateTime->addSeconds(2)->toIso());
        $this->assertSame('2016-09-08 22:06:59', $dateTime->addSeconds(-3)->toIso());
    }
}
