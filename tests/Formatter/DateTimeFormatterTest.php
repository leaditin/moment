<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests\Formatter;

use Leaditin\Moment\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Leaditin\Moment\Formatter\DateTimeFormatter}
 *
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateTimeFormatterTest extends TestCase
{
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
            ['1984-07-26', DateTime::TYPE_DATE, '26 Jul 1984'],
            ['1984-07-26 12:05', DateTime::TYPE_DATETIME, '26 Jul 1984'],
            ['26 Jul 1984', DateTime::TYPE_DATE, '26 Jul 1984'],
            ['26 Jul 1984 12:05', DateTime::TYPE_DATETIME, '26 Jul 1984']
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
            ['1984-07-26 12:15:30', DateTime::TYPE_DATE, '26 Jul 1984'],
            ['1984-07-26 12:15:30', DateTime::TYPE_DATETIME, '26 Jul 1984, 12:15'],
            ['1984-07-26', DateTime::TYPE_DATE, '26 Jul 1984'],
            ['1984-07-26', DateTime::TYPE_DATETIME, '26 Jul 1984, 00:00']
        ];
    }
}
