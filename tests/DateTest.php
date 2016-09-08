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

final class DateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromDateTime()
    {
        $input = new \DateTime();
        $date = Date::createFromDateTime($input);

        $this->assertInstanceOf(Date::class, $date);
        $this->assertEquals($input->format('Y-m-d'), $date->toIso());

        $input = new DateTime();
        $date = Date::createFromDateTime($input);

        $this->assertInstanceOf(Date::class, $date);
        $this->assertSame($input->toIsoDate(), $date->toIso());

        $input = new Date();
        $date = Date::createFromDateTime($input);

        $this->assertSame($input, $date);
    }

    public function testCreateFromTimestamp()
    {
        $input = time();
        $date = Date::createFromTimestamp($input);

        $this->assertInstanceOf(Date::class, $date);
        $this->assertSame(date('Y-m-d', $input), $date->toIso());
    }

    public function testConstruct()
    {
        $date = new Date();

        $this->assertSame(date('Y-m-d'), $date->toIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('2016-09-08');

        $this->assertSame('2016-09-08', $date->toIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('2016-09-08 22:07:02');

        $this->assertSame('2016-09-08', $date->toIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));

        $date = new Date('@'.strtotime('2016-09-08 22:07:02'));

        $this->assertSame('2016-09-08', $date->toIso());
        $this->assertSame('00:00:00', $date->format('H:i:s'));
    }

    public function testCreate()
    {
        $date = Date::create(2016, 9, 8);

        $this->assertSame('2016-09-08', $date->toIso());
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

    public function testToIso()
    {
        $input = '2016-09-08';

        $this->assertSame($input, (new Date($input))->toIso());
    }

    public function toDateTime()
    {
        $date = new Date();
        $dateTime = $date->toDateTime();

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame($date->toIso(), $dateTime->toIsoDate());
        $this->assertSame('00:00:00', $dateTime->toIsoTime());
    }
}
