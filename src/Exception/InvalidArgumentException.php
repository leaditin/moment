<?php declare(strict_types=1);

namespace Leaditin\Moment\Exception;

use Leaditin\Moment\DateTime;
use Leaditin\Moment\Time;

/**
 * Class InvalidArgumentException
 *
 * @package Leaditin\Moment\Exception
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     *
     * @return InvalidArgumentException
     */
    public static function startDateNotBeforeEndDate(DateTime $startDate, DateTime $endDate): InvalidArgumentException
    {
        return new self(sprintf(
            'Start date "%s" in date range must be before the end date "%s"',
            $startDate->getLong(), $endDate->getLong()
        ));
    }

    /**
     * @param string|int $time
     *
     * @return InvalidArgumentException
     */
    public static function invalidTimeFormat($time): InvalidArgumentException
    {
        return new self(sprintf(
            'Invalid time format "%s" provided as argument for "%s"', $time, Time::class
        ));
    }

    /**
     * @return InvalidArgumentException
     */
    public static function dateRangeWithoutBoundaries(): InvalidArgumentException
    {
        return new self(sprintf(
            'Date Range must have either %s or %s defined',
            '$startDate', '$endDate'
        ));
    }
}
