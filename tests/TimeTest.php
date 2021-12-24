<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests;

use Leaditin\Moment\Exception\InvalidArgumentException;
use Leaditin\Moment\Time;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Leaditin\Moment\Time}
 *
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class TimeTest extends TestCase
{
    /**
     * @param string|int $actual
     * @dataProvider invalidTimeProvider
     */
    public function testInvalidTime($actual)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Invalid time format "%s" provided as argument for "%s"', $actual, Time::class
        ));

        Time::fromString($actual);
    }

    /**
     * @return array
     */
    public function invalidTimeProvider() : array
    {
        return [
            ['string'],
            ['12 35'],
        ];
    }

    /**
     * @param string|int $actual
     * @param int $expected
     * @dataProvider getHoursProvider
     */
    public function testGetHours($actual, int $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getHours());
    }

    /**
     * @return array
     */
    public function getHoursProvider() : array
    {
        return [
            ['12:15', 12],
            ['14:45:55', 14],
            [3600, 1],
        ];
    }


    /**
     * @param string|int $actual
     * @param int $expected
     * @dataProvider getMinutesProvider
     */
    public function testGetMinutes($actual, int $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getMinutes());
    }

    /**
     * @return array
     */
    public function getMinutesProvider() : array
    {
        return [
            ['12:15', 15],
            ['14:45:55', 45],
            [3600, 0],
        ];
    }

    /**
     * @param string|int $actual
     * @param int $expected
     * @dataProvider getSecondsProvider
     */
    public function testGetSeconds($actual, int $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getSeconds());
    }

    /**
     * @return array
     */
    public function getSecondsProvider() : array
    {
        return [
            ['12:15', 0],
            ['14:45:55', 55],
            [3600, 0],
        ];
    }

    /**
     * @param string|int $actual
     * @param int $expected
     * @dataProvider getTotalHoursProvider
     */
    public function testGetTotalHours($actual, int $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getTotalHours());
    }

    /**
     * @return array
     */
    public function getTotalHoursProvider() : array
    {
        return [
            ['12:15', 12],
            ['14:45:55', 14],
            [3600, 1],
        ];
    }


    /**
     * @param string|int $actual
     * @param int $expected
     * @dataProvider getTotalMinutesProvider
     */
    public function testGetTotalMinutes($actual, int $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getTotalMinutes());
    }

    /**
     * @return array
     */
    public function getTotalMinutesProvider() : array
    {
        return [
            ['12:15', 735],
            ['14:45:55', 885],
            [3600, 60],
        ];
    }

    /**
     * @param string|int $actual
     * @param int $expected
     * @dataProvider getTotalSecondsProvider
     */
    public function testGetTotalSeconds($actual, int $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, $time->getTotalSeconds());
    }

    /**
     * @return array
     */
    public function getTotalSecondsProvider() : array
    {
        return [
            ['12:15', 44100],
            ['14:45:55', 53155],
            [3600, 3600],
        ];
    }

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
     * @param string|int $actual
     * @param string $expected
     * @dataProvider toStringProvider
     */
    public function testToString($actual, string $expected)
    {
        $time = $this->createTime($actual);

        self::assertEquals($expected, (string)$time);
    }

    /**
     * @return array
     */
    public function toStringProvider() : array
    {
        return [
            ['23:35', '23:35'],
            ['14:35:26', '14:35'],
            [61, '00:01'],
            [53155, '14:45'],
        ];
    }

    /**
     * @param string|int $time1
     * @param string|int $time2
     * @param int $expected
     * @dataProvider addTimeProvider
     */
    public function testAddTime($time1, $time2, int $expected)
    {
        $time = $this->createTime($time1);
        $time->addTime($this->createTime($time2));

        self::assertEquals($expected, $time->getTotalSeconds());
    }

    /**
     * @return array
     */
    public function addTimeProvider() : array
    {
        return [
            ['12:35', 3672, 48972],
            [3600, '11:35:23', 45323],
            [3600, 3600, 7200],
            ['01:05', '00:05', 4200],
        ];
    }

    /**
     * @param string|int $time1
     * @param string|int $time2
     * @param int $expected
     * @dataProvider subTimeProvider
     */
    public function testSubTime($time1, $time2, int $expected)
    {
        $time = $this->createTime($time1);
        $time->subTime($this->createTime($time2));

        self::assertEquals($expected, $time->getTotalSeconds());
    }

    /**
     * @return array
     */
    public function subTimeProvider() : array
    {
        return [
            ['01:05', '00:05', 3600],
            [7200, 3600, 3600],
            ['12:35', 3672, 41628],
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
