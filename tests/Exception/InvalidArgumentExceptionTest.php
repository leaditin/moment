<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests\Exception;

use Leaditin\Moment\DateTime;
use Leaditin\Moment\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Leaditin\Moment\Exception\InvalidArgumentException}
 *
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class InvalidArgumentExceptionTest extends TestCase
{
    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @dataProvider startDateNotBeforeEndDateProvider
     */
    public function testStartDateNotBeforeEndDateException(DateTime $startDate, DateTime $endDate)
    {
        $ex = InvalidArgumentException::startDateNotBeforeEndDate($startDate, $endDate);

        self::assertInstanceOf(InvalidArgumentException::class, $ex);
    }

    /**
     * @return array
     */
    public function startDateNotBeforeEndDateProvider() : array
    {
        return [
            [new DateTime('2017-03-08'), new DateTime('2016-12-04')],
            [new DateTime('12 Jul 2015'), new DateTime('26 Jun 2015')],
        ];
    }

    /**
     * @param string|int $time
     * @dataProvider startDateNotBeforeEndDateProvider
     */
    public function testInvalidTimeFormatException($time)
    {
        $ex = InvalidArgumentException::invalidTimeFormat($time);

        self::assertInstanceOf(InvalidArgumentException::class, $ex);
    }

    /**
     * @return array
     */
    public function invalidTimeFormatProvider() : array
    {
        return [
            ['string'],
            ['13 24'],
        ];
    }

    /**
     *
     */
    public function testDateRangeWithoutBoundariesException()
    {
        $ex = InvalidArgumentException::dateRangeWithoutBoundaries();

        self::assertInstanceOf(InvalidArgumentException::class, $ex);
    }
}
