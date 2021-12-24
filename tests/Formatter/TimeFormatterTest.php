<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests\Formatter;

use Leaditin\Moment\Time;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Leaditin\Moment\Formatter\TimeFormatter}
 *
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class TimeFormatterTest extends TestCase
{
    /**
     * @param string|int $actual
     * @param string $expected
     * @dataProvider getShortProvider
     */
    public function testGetShort($actual, string $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getShort());
    }

    /**
     * @return array
     */
    public function getShortProvider() : array
    {
        return [
            [720, '00:12'],
            ['12:15:30', '12:15'],
            ['12:15', '12:15'],
        ];
    }

    /**
     * @param string|int $actual
     * @param string $expected
     * @dataProvider getLongProvider
     */
    public function testGetLong($actual, string $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getLong());
    }

    /**
     * @return array
     */
    public function getLongProvider() : array
    {
        return [
            [4500, '01:15:00'],
            ['14:12:36', '14:12:36'],
            ['15:23', '15:23:00'],
        ];
    }

    /**
     * @param string|int $time
     * @return Time
     */
    private function createTime($time) : Time
    {
        if (is_string($time)) {
            return Time::fromString($time);
        } elseif (is_int($time)) {
            return Time::fromSeconds($time);
        }
    }
}
