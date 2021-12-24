<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests\Formatter;

use Leaditin\Moment\DateRange;
use Leaditin\Moment\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \Leaditin\Moment\Formatter\DateRangeFormatter}
 *
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateRangeFormatterTest extends TestCase
{
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
    public function getShortProvider() : array
    {
        return [
            [new DateRange(), 'Ongoing'],
            [new DateRange(null, new DateTime('1984-07-26')), 'Ongoing - 26 Jul 1984'],
            [new DateRange(new DateTime('1984-07-26'), null), '26 Jul 1984 - Ongoing'],
            [new DateRange(new DateTime('1984-07-26'), new DateTime('1984-07-26')), '26 Jul 1984'],
            [new DateRange(new DateTime('1984-07-26'), new DateTime('1984-07-27')), '26 Jul 1984 - 27 Jul 1984'],
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
    public function getLongProvider() : array
    {
        return [
            [
                new DateRange(),
                'Ongoing'
            ],
            [
                new DateRange(
                    null,
                    new DateTime('1984-07-26 12:00')
                ),
                'Ongoing - 26 Jul 1984, 12:00'
            ],
            [
                new DateRange(
                    new DateTime('1984-07-26 14:15'),
                    null
                ),
                '26 Jul 1984, 14:15 - Ongoing'
            ],
            [
                new DateRange(
                    new DateTime('1984-07-26 12:00'),
                    new DateTime('1984-07-26 12:00')
                ),
                '26 Jul 1984, 12:00'
            ],
            [
                new DateRange(
                    new DateTime('1984-07-26 12:00'),
                    new DateTime('1984-07-26 14:00')
                ),
                '26 Jul 1984, 12:00 - 14:00'
            ],
            [
                new DateRange(
                    new DateTime('1984-07-26 12:00', null, DateTime::TYPE_DATE),
                    new DateTime('1984-07-26 14:00', null, DateTime::TYPE_DATE)
                ),
                '26 Jul 1984'
            ],
            [
                new DateRange(
                    new DateTime('1984-07-26 00:00'),
                    new DateTime('1984-07-27 12:00')
                ),
                '26 Jul 1984, 00:00 - 27 Jul 1984, 12:00'
            ],
        ];
    }
}
