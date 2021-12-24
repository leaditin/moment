<?php declare(strict_types=1);

namespace Leaditin\Moment\Comparator;

use Leaditin\Moment\DateTime;

/**
 * Class DateTimeComparator
 *
 * @package Leaditin\Moment\Comparator
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateTimeComparator
{
    /**
     * @param DateTime|null $endDate1
     * @param DateTime|null $endDate2
     *
     * @return DateTime|null
     */
    public static function getLatestDate(DateTime $endDate1 = null, DateTime $endDate2 = null): ?DateTime
    {
        if ($endDate1 === null) {
            return $endDate2;
        }

        if ($endDate2 === null) {
            return $endDate1;
        }

        return max($endDate1, $endDate2);
    }

    /**
     * @param null|DateTime $startDate1
     * @param null|DateTime $date2
     *
     * @return DateTime|null
     */
    public static function getEarliestDate(DateTime $startDate1 = null, DateTime $date2 = null): ?DateTime
    {
        if ($startDate1 === null) {
            return $date2;
        }

        if ($date2 === null) {
            return $startDate1;
        }

        return min($startDate1, $date2);
    }
}
