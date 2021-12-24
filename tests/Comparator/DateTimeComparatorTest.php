<?php

declare(strict_types = 1);

namespace Leaditin\Moment\Tests\Comparator;

use Leaditin\Moment\Comparator\DateTimeComparator;
use Leaditin\Moment\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeComparatorTest extends TestCase
{
    /**
     * @param DateTime|null $endDate1
     * @param DateTime|null $endDate2
     * @param DateTime|null $expected
     * @dataProvider earliestDateProvider
     */
    public function testGetEarliestDate(DateTime $endDate1 = null, DateTime $endDate2 = null, DateTime $expected = null)
    {
        $earliestDate = DateTimeComparator::getEarliestDate($endDate1, $endDate2);

        self::assertEquals($expected, $earliestDate);
    }

    /**
     * @return array
     */
    public function earliestDateProvider() : array
    {
        return [
            [null, null, null],
            [null, new DateTime('1984-07-26'), new DateTime('1984-07-26')],
            [new DateTime('1984-07-26'), null, new DateTime('1984-07-26')],
            [new DateTime('1984-07-26'), new DateTime('1987-05-07'), new DateTime('1984-07-26')],
        ];
    }

    /**
     * @param DateTime|null $endDate1
     * @param DateTime|null $endDate2
     * @param DateTime|null $expected
     * @dataProvider latestDateProvider
     */
    public function testGetLatestDate(DateTime $endDate1 = null, DateTime $endDate2 = null, DateTime $expected = null)
    {
        $latestDate = DateTimeComparator::getLatestDate($endDate1, $endDate2);

        self::assertEquals($expected, $latestDate);
    }

    /**
     * @return array
     */
    public function latestDateProvider() : array
    {
        return [
            [null, null, null],
            [null, new DateTime('1984-07-26'), new DateTime('1984-07-26')],
            [new DateTime('1984-07-26'), null, new DateTime('1984-07-26')],
            [new DateTime('1984-07-26'), new DateTime('1987-05-07'), new DateTime('1987-05-07')],
        ];
    }
}
