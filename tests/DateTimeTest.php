<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests;

use Leaditin\Moment\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Leaditin\Moment\DateTime}
 *
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateTimeTest extends TestCase
{
    /**
     * @param string $a
     * @param string $b
     * @param bool $expected
     * @dataProvider isBeforeProvider
     */
    public function testIsBefore(string $a, string $b, bool $expected)
    {
        $date1 = new DateTime($a);
        $date2 = new DateTime($b);

        self::assertSame($expected, $date1->isBefore($date2));
    }

    /**
     * @param string $a
     * @param string $b
     * @param bool $expected
     * @dataProvider isBeforeProvider
     */
    public function testIsAfter(string $a, string $b, bool $expected)
    {
        $date1 = new DateTime($a);
        $date2 = new DateTime($b);

        self::assertSame($expected, $date2->isAfter($date1));
    }

    /**
     * @return array
     */
    public function isBeforeProvider() : array
    {
        return [
            ['2016-12-01', '2016-12-03', true],
            ['2016-12-01', '2016-11-03', false],
            ['2016-12-01 14:13', '2016-12-01 14:13', false],
            ['2016-12-01 12:00:00', '2016-12-01 12:00:01', true],
        ];
    }

    /**
     * @param string $actual
     * @param bool $expected
     * @dataProvider isTodayProvider
     */
    public function testIsToday(string $actual, bool $expected)
    {
        $date = new DateTime($actual);

        self::assertSame($expected, $date->isToday());
    }

    /**
     * @return array
     */
    public function isTodayProvider() : array
    {
        return [
            ['now', true],
            ['1970-12-31', false],
        ];
    }

    /**
     * @param string $a
     * @param string $b
     * @param bool $expected
     * @dataProvider isEqualProvider
     */
    public function testIsEqualTo($a, $b, $expected)
    {
        $date1 = new DateTime($a);
        $date2 = new DateTime($b);

        self::assertSame($expected, $date1->isEqualTo($date2));
    }

    /**
     * @return array
     */
    public function isEqualProvider() : array
    {
        return [
            ['2016-12-01', '2016-12-03', false],
            ['2016-12-01', '2016-12-01', true],
            ['2016-12-01 14:13', '2016-12-01 14:13', true],
            ['2016-12-01 14:13:00', '2016-12-01 14:13:05', false],
        ];
    }

    /**
     * @param string $time
     * @param string $dateType
     * @param string $expected
     * @dataProvider getDateTypeProvider
     */
    public function testGetDateType(string $time, string $dateType, string $expected)
    {
        $dateTime = new DateTime($time, new \DateTimeZone('UTC'), $dateType);

        self::assertSame($expected, $dateTime->getDateType());
    }

    /**
     * @return array
     */
    public function getDateTypeProvider() : array
    {
        return [
            ['1984-07-26', DateTime::TYPE_DATE, DateTime::TYPE_DATE],
            ['O1 Sep 2015 16:38', DateTime::TYPE_DATETIME, DateTime::TYPE_DATETIME]
        ];
    }

    /**
     * @param string $time
     * @param string $dateType
     * @param string $expected
     * @dataProvider getShortProvider
     */
    public function testGetShort(string $time, string $dateType, string $expected)
    {
        $dateTime = new DateTime($time, new \DateTimeZone('UTC'), $dateType);

        self::assertSame($expected, $dateTime->getShort());
    }

    /**
     * @return array
     */
    public function getShortProvider() : array
    {
        return [
            ['2016-11-08', DateTime::TYPE_DATE, '08 Nov 2016'],
            ['2016-11-08 12:05', DateTime::TYPE_DATETIME, '08 Nov 2016'],
            ['08 Nov 2016', DateTime::TYPE_DATE, '08 Nov 2016'],
            ['08 Nov 2016 12:05', DateTime::TYPE_DATETIME, '08 Nov 2016']
        ];
    }

    /**
     * @param string $time
     * @param string $dateType
     * @param string $expected
     * @dataProvider getLongProvider
     */
    public function testGetLong(string $time, string $dateType, string $expected)
    {
        $dateTime = new DateTime($time, new \DateTimeZone('UTC'), $dateType);

        self::assertSame($expected, $dateTime->getLong());
    }

    /**
     * @return array
     */
    public function getLongProvider() : array
    {
        return [
            ['2016-11-08 12:15:30', DateTime::TYPE_DATE, '08 Nov 2016'],
            ['2016-11-08 12:15:30', DateTime::TYPE_DATETIME, '08 Nov 2016, 12:15'],
            ['2016-11-08', DateTime::TYPE_DATE, '08 Nov 2016'],
            ['2016-11-08', DateTime::TYPE_DATETIME, '08 Nov 2016, 00:00']
        ];
    }

    /**
     * @param string $time
     * @param string $dateType
     * @param string $expected
     * @dataProvider getUnixFormatProvider
     */
    public function testGetUnixFormat(string $time, string $dateType, string $expected)
    {
        $dateTime = new DateTime($time, new \DateTimeZone('UTC'), $dateType);

        self::assertSame($expected, $dateTime->getUnixTimestamp());
    }

    /**
     * @return array
     */
    public function getUnixFormatProvider() : array
    {
        return [
            ['2016-11-08 12:15:30', DateTime::TYPE_DATE, '2016-11-08'],
            ['2016-11-08 12:15:30', DateTime::TYPE_DATETIME, '2016-11-08 12:15:30'],
            ['2016-11-08', DateTime::TYPE_DATE, '2016-11-08'],
            ['2016-11-08', DateTime::TYPE_DATETIME, '2016-11-08 00:00:00']
        ];
    }

    /**
     * @param $time
     * @param $dateType
     * @param $expected
     * @dataProvider toStringProvider
     */
    public function testToString($time, $dateType, $expected)
    {
        $dateTime = new DateTime($time, new \DateTimeZone('UTC'), $dateType);

        self::assertEquals($expected, (string)$dateTime);
    }

    /**
     * @return array
     */
    public function toStringProvider()
    {
        $today = '2016-11-08';
        $now = '2016-11-08 12:15:30';

        return [
            [$now, DateTime::TYPE_DATE, '08 Nov 2016'],
            [$now, DateTime::TYPE_DATETIME, '08 Nov 2016, 12:15'],
            [$today, DateTime::TYPE_DATE, '08 Nov 2016'],
            [$today, DateTime::TYPE_DATETIME, '08 Nov 2016, 00:00']
        ];
    }

    /**
     * @param DateTime $dateTime
     * @param DateTime $expected
     * @dataProvider getGmtDateTimeProvider
     */
    public function testGetGmtDateTime(DateTime $dateTime, DateTime $expected)
    {
        self::assertEquals($expected, $dateTime->getGmtDateTime());
    }

    /**
     * @return array
     */
    public function getGmtDateTimeProvider() : array
    {
        return [
            [
                new DateTime('2016-11-08 12:15:30', new \DateTimeZone('Europe/Belgrade')),
                new DateTime('2016-11-08 11:15:30', new \DateTimeZone('Europe/London'))
            ],
            [
                new DateTime('2016-11-08', new \DateTimeZone('Europe/Belgrade')),
                new DateTime('2016-11-07 23:00:00', new \DateTimeZone('UTC'))
            ],
        ];
    }
}
