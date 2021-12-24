<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests;

use Leaditin\Moment\DateRange;
use Leaditin\Moment\DateTime;
use Leaditin\Moment\Time;
use Leaditin\Moment\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Leaditin\Moment\DateRange}
 *
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateRangeTest extends TestCase
{
    public function testGetDates()
    {
        $startDate = new DateTime('2016-11-08 12:00');
        $endDate = new DateTime('2016-11-08 13:00');
        $dateRange = new DateRange($startDate, $endDate);

        self::assertInstanceOf(DateTime::class, $dateRange->getStartDate());
        self::assertInstanceOf(DateTime::class, $dateRange->getEndDate());
        self::assertEquals($startDate, $dateRange->getStartDate());
        self::assertEquals($endDate, $dateRange->getEndDate());
        self::assertNotSame($startDate, $dateRange->getStartDate());
        self::assertNotSame($endDate, $dateRange->getEndDate());
    }

    public function testInvalidDates()
    {
        $startDate = new DateTime('2016-11-09');
        $endDate = new DateTime('2016-11-08');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Start date "%s" in date range must be before the end date "%s"',
            $startDate->getLong(), $endDate->getLong()
        ));

        new DateRange($startDate, $endDate);
    }

    /**
     * @param DateTime|null $startDate
     * @param DateTime|null $endDate
     * @param DateTime|null $refDate
     * @param bool $expected
     * @dataProvider containsDateProvider
     */
    public function testContainsDate(DateTime $startDate = null, DateTime $endDate = null, DateTime $refDate, bool $expected)
    {
        $dateRange = new DateRange($startDate, $endDate);

        self::assertSame($expected, $dateRange->containsDate($refDate));
    }

    /**
     * @return array
     */
    public function containsDateProvider() : array
    {
        return [
            [null, null, new DateTime('2016-11-09'), true],
            [null, new DateTime('2016-11-09'), new DateTime('2016-11-09'), true],
            [null, new DateTime('2016-11-09'), new DateTime('2016-11-10'), false],
            [new DateTime('2016-11-09'), null, new DateTime('2016-11-09'), true],
            [new DateTime('2016-11-09'), null, new DateTime('2016-11-08'), false],
            [new DateTime('2016-11-08'), new DateTime('2016-11-10'), new DateTime('2016-11-09'), true],
            [new DateTime('2016-11-08'), new DateTime('2016-11-09'), new DateTime('2016-11-10'), false],
        ];
    }

    /**
     * @param DateRange|null $dateRange1
     * @param DateRange|null $dateRange2
     * @param bool $expected
     * @dataProvider containsDateRangeProvider
     */
    public function testContainsDateRange(DateRange $dateRange1 = null, DateRange $dateRange2 = null, bool $expected)
    {
        self::assertSame($expected, $dateRange1->containsDateRange($dateRange2));
    }

    /**
     * @return array
     */
    public function containsDateRangeProvider()
    {
        $today = new DateTime('2016-11-08', new \DateTimeZone('UTC'), DateTime::TYPE_DATE);

        return [
            [
                new DateRange(),
                new DateRange(),
                true
            ],
            [
                new DateRange(),
                new DateRange($today, $today),
                true
            ],
            [
                new DateRange(),
                new DateRange(null, $today),
                true
            ],
            [
                new DateRange(),
                new DateRange($today, null),
                true
            ],
            [
                new DateRange(null, $today),
                new DateRange(null, $today),
                true
            ],
            [
                new DateRange($today, null),
                new DateRange(null, $today),
                false
            ],
            [
                new DateRange(new DateTime('2016-11-08'), new DateTime('2016-11-30')),
                new DateRange(null, $today),
                false
            ],
            [
                new DateRange(new DateTime('2016-11-08'), new DateTime('2016-11-30')),
                new DateRange($today, null),
                false
            ],
            [
                new DateRange(new DateTime('2016-11-08'), new DateTime('2016-11-30')),
                new DateRange(new DateTime('2016-11-08'), new DateTime('2016-11-30')),
                true
            ],
            [
                new DateRange(new DateTime('2016-11-08 12:00'), new DateTime('2016-11-30 12:00')),
                new DateRange(new DateTime('2016-11-08 14:00'), new DateTime('2016-11-30 10:00')),
                true
            ],
        ];
    }

    /**
     * @param DateRange $dateRange1
     * @param DateRange $dateRange2
     * @param bool $expected
     * @dataProvider overlapsDateRangeProvider
     */
    public function testOverlapsDateRange(DateRange $dateRange1, DateRange $dateRange2, $expected)
    {
        self::assertSame($expected, $dateRange1->overlapsDateRange($dateRange2));
    }

    /**
     * @return array
     */
    public function overlapsDateRangeProvider() : array
    {
        $today = new DateTime('2016-11-08', new \DateTimeZone('UTC'), DateTime::TYPE_DATE);

        return [
            [
                new DateRange(),
                new DateRange(),
                true
            ],
            [
                new DateRange(),
                new DateRange(null, $today),
                true
            ],
            [
                new DateRange(null, $today),
                new DateRange(null, $today),
                true
            ],
            [
                new DateRange($today, null),
                new DateRange(null, $today),
                false
            ],
            [
                new DateRange(new DateTime('2016-11-01'), new DateTime('2016-11-30')),
                new DateRange(new DateTime('2016-11-10'), new DateTime('2016-11-20')),
                true
            ],
            [
                new DateRange(new DateTime('2016-11-10'), new DateTime('2016-11-20')),
                new DateRange(new DateTime('2016-11-01'), new DateTime('2016-11-30')),
                true
            ]
        ];
    }

    /**
     * @param DateRange $dateRange
     * @param DateTime $date
     * @param bool $expected
     * @dataProvider isBeforeProvider
     */
    public function testIsBefore(DateRange $dateRange, DateTime $date, bool $expected)
    {
        self::assertSame($expected, $dateRange->isBefore($date));
    }

    /**
     * @return array
     */
    public function isBeforeProvider() : array
    {
        return [
            [new DateRange(new DateTime('2016-11-08'), null), new DateTime('2016-11-08'), false],
            [new DateRange(null, new DateTime('2016-11-08')), new DateTime('2016-11-08'), true],
            [new DateRange(new DateTime('2016-11-07'), new DateTime('2016-11-08')), new DateTime('2016-11-09'), true],
            [new DateRange(new DateTime('2016-11-07'), new DateTime('2016-11-08')), new DateTime('2016-11-06'), false],
            [new DateRange(new DateTime('2016-11-07'), new DateTime('2016-11-09')), new DateTime('2016-11-08'), false],
        ];
    }

    /**
     * @param DateRange $dateRange
     * @param DateTime $date
     * @param bool $expected
     * @dataProvider isAfterProvider
     */
    public function testIsAfter(DateRange $dateRange, DateTime $date, $expected)
    {
        self::assertEquals($expected, $dateRange->isAfter($date));
    }

    /**
     * @return array
     */
    public function isAfterProvider()
    {
        return [
            [new DateRange(new DateTime('2016-11-08'), null), new DateTime('2016-11-08'), true],
            [new DateRange(null, new DateTime('2016-11-08')), new DateTime('2016-11-08'), false],
            [new DateRange(new DateTime('2016-11-07'), new DateTime('2016-11-08')), new DateTime('2016-11-09'), false],
            [new DateRange(new DateTime('2016-11-07'), new DateTime('2016-11-08')), new DateTime('2016-11-06'), true],
            [new DateRange(new DateTime('2016-11-07'), new DateTime('2016-11-09')), new DateTime('2016-11-08'), false],
        ];
    }

    /**
     * @param DateRange $dateRange
     * @param bool $expected
     * @dataProvider isNowProvider
     */
    public function testIsNow(DateRange $dateRange, $expected)
    {
        self::assertSame($expected, $dateRange->isNow());
    }

    /**
     * @return array
     */
    public function isNowProvider() : array
    {
        $today = new DateTime();
        $today->setTime(0, 0);

        $tomorrow = clone $today;
        $tomorrow->add(new \DateInterval('P1D'));

        return [
            [new DateRange($today, $tomorrow), true],
        ];
    }

    /**
     * @param DateRange $dateRange
     * @param string $expected
     * @dataProvider getShortProvider
     */
    public function testGetShort(DateRange $dateRange, string $expected)
    {
        self::assertSame($expected, $dateRange->getShort('Ongoing'));
    }

    /**
     * @return array
     */
    public function getShortProvider()
    {
        return [
            [new DateRange(), 'Ongoing'],
            [new DateRange(null, new DateTime('2016-11-08')), 'Ongoing - 08 Nov 2016'],
            [new DateRange(new DateTime('2016-11-08'), null), '08 Nov 2016 - Ongoing'],
            [new DateRange(new DateTime('2016-11-08'), new DateTime('2016-11-08')), '08 Nov 2016'],
            [new DateRange(new DateTime('2016-11-08'), new DateTime('2016-11-10')), '08 Nov 2016 - 10 Nov 2016'],
        ];
    }

    /**
     * @param DateRange $dateRange
     * @param string $expected
     * @dataProvider getLongProvider
     */
    public function testGetLong(DateRange $dateRange, string $expected)
    {
        self::assertEquals($expected, $dateRange->getLong('Ongoing'));
    }

    /**
     * @return array
     */
    public function getLongProvider()
    {
        return [
            [
                new DateRange(),
                'Ongoing'
            ],
            [
                new DateRange(
                    null,
                    new DateTime('2016-11-08 12:00')
                ),
                'Ongoing - 08 Nov 2016, 12:00'
            ],
            [
                new DateRange(
                    new DateTime('2016-11-08 14:15'),
                    null
                ),
                '08 Nov 2016, 14:15 - Ongoing'
            ],
            [
                new DateRange(
                    new DateTime('2016-11-08 12:00'),
                    new DateTime('2016-11-08 12:00')
                ),
                '08 Nov 2016, 12:00'
            ],
            [
                new DateRange(
                    new DateTime('2016-11-08 12:00'),
                    new DateTime('2016-11-08 14:00')
                ),
                '08 Nov 2016, 12:00 - 14:00'
            ],
            [
                new DateRange(
                    new DateTime('2016-11-08 12:00', null, DateTime::TYPE_DATE),
                    new DateTime('2016-11-08 14:00', null, DateTime::TYPE_DATE)
                ),
                '08 Nov 2016'
            ],
            [
                new DateRange(
                    new DateTime('2016-11-08 00:00'),
                    new DateTime('2016-11-10 12:00')
                ),
                '08 Nov 2016, 00:00 - 10 Nov 2016, 12:00'
            ],
        ];
    }

    /**
     * @param DateRange $dateRange
     * @param string $expected
     * @dataProvider getLongProvider
     */
    public function testToString(DateRange $dateRange, string $expected)
    {
        self::assertSame($expected, (string)$dateRange);
    }

    public function testGetAllDates()
    {
        $dateRange = new DateRange(
            new DateTime('2016-11-08 12:00'),
            new DateTime('2016-11-10 10:00')
        );

        $allDates = $dateRange->getAllDates();
        self::assertCount(3, $allDates);

        $date = reset($allDates);
        self::assertInstanceOf(DateTime::class, $date);
        self::assertEquals('2016-11-08', $date->format('Y-m-d'));
    }

    /**
     * @param DateRange $dateRange
     * @dataProvider getInfinityDateRangesProvider
     */
    public function testGetAllDatesWillThrowException(DateRange $dateRange)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Date Range must have either %s or %s defined',
            '$startDate', '$endDate'
        ));

        $dateRange->getAllDates();
    }

    /**
     * @return array
     */
    public function getInfinityDateRangesProvider()
    {
        return [
            [new DateRange(null, new DateTime('2016-11-10'))],
            [new DateRange(new DateTime('2016-11-10'), null)],
            [new DateRange()],
        ];
    }

    /**
     * @param DateRange $dateRange1
     * @param DateRange $dateRange2
     * @param DateRange|null $expected
     * @dataProvider intersectProvider
     */
    public function testIntersect(DateRange $dateRange1, DateRange $dateRange2, $expected)
    {
        self::assertEquals($expected, $dateRange1->intersect($dateRange2));
    }

    /**
     * @return array
     */
    public function intersectProvider()
    {
        return [
            [
                new DateRange(
                    new DateTime('2016-11-08', new \DateTimeZone('UTC'), DateTime::TYPE_DATE),
                    new DateTime('2016-11-16', new \DateTimeZone('UTC'), DateTime::TYPE_DATE)
                ),
                new DateRange(
                    new DateTime('2016-11-12', new \DateTimeZone('UTC'), DateTime::TYPE_DATE),
                    new DateTime('2016-11-16', new \DateTimeZone('UTC'), DateTime::TYPE_DATE)
                ),
                new DateRange(
                    new DateTime('2016-11-12', new \DateTimeZone('UTC'), DateTime::TYPE_DATE),
                    new DateTime('2016-11-16', new \DateTimeZone('UTC'), DateTime::TYPE_DATE)
                )
            ],
            [
                new DateRange(
                    null,
                    new DateTime('2016-11-08', new \DateTimeZone('UTC'), DateTime::TYPE_DATE)
                ),
                new DateRange(
                    new DateTime('2016-11-12', new \DateTimeZone('UTC'), DateTime::TYPE_DATE),
                    null
                ),
                null
            ],
            [
                new DateRange(
                    null,
                    new DateTime('2016-11-08', new \DateTimeZone('UTC'), DateTime::TYPE_DATE)
                ),
                new DateRange(
                    new DateTime('2016-11-06', new \DateTimeZone('UTC'), DateTime::TYPE_DATE),
                    null
                ),
                new DateRange(
                    new DateTime('2016-11-06', new \DateTimeZone('UTC'), DateTime::TYPE_DATE),
                    new DateTime('2016-11-08', new \DateTimeZone('UTC'), DateTime::TYPE_DATE)
                )
            ],
        ];
    }

    /**
     * @param DateRange $dateRange
     * @param Time|null $expected
     * @dataProvider getDurationProvider
     */
    public function testGetDuration(DateRange $dateRange, $expected)
    {
        self::assertEquals($expected, $dateRange->getDuration());
    }

    /**
     * @return array
     */
    public function getDurationProvider() : array
    {
        return [
            [new DateRange(), null],
            [new DateRange(null, new DateTime()), null],
            [new DateRange(new DateTime(), null), null],
            [new DateRange(new DateTime('2016-11-08 12:00:00'), new DateTime('2016-11-08 14:00:00')), Time::fromSeconds(7200)],
        ];
    }

    public function testClone()
    {
        $dateRange = new DateRange(
            new DateTime('2016-11-08 12:00'),
            new DateTime('2016-11-10 12:00')
        );

        $dateRange2 = clone $dateRange;

        self::assertEquals($dateRange, $dateRange2);
        self::assertEquals($dateRange->getStartDate(), $dateRange2->getStartDate());
        self::assertEquals($dateRange->getEndDate(), $dateRange2->getEndDate());
        self::assertNotSame($dateRange->getStartDate(), $dateRange2->getStartDate());
        self::assertNotSame($dateRange->getEndDate(), $dateRange2->getEndDate());
    }
}
