<?php declare(strict_types = 1);

namespace Leaditin\Moment\Formatter;

use Leaditin\Moment\DateRange;
use Leaditin\Moment\DateTime;

/**
 * Class DateRangeFormatter
 *
 * @package Leaditin\Moment\Formatter
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateRangeFormatter implements FormatterInterface
{
    /** @var DateTime */
    private $startDate;

    /** @var DateTime */
    private $endDate;

    /**
     * @param DateRange $dateRange
     */
    public function __construct(DateRange $dateRange)
    {
        $this->startDate = $dateRange->getStartDate();
        $this->endDate = $dateRange->getEndDate();
    }

    /**
     * {@inheritdoc}
     */
    public function getShort(string $nullPlaceholder = 'Ongoing') : string
    {
        if ($this->startDate === null && $this->endDate === null) {
            return $nullPlaceholder;
        }

        if ($this->startDate === null) {
            return $nullPlaceholder . ' - ' . $this->endDate->getShort();
        }

        if ($this->endDate === null) {
            return $this->startDate->getShort() . ' - ' . $nullPlaceholder;
        }

        if ($this->startDate->getShort() === $this->endDate->getShort()) {
            return $this->startDate->getShort();
        }

        return $this->startDate->getShort() . ' - ' . $this->endDate->getShort();
    }

    /**
     * {@inheritdoc}
     */
    public function getLong(string $nullPlaceholder = 'Ongoing') : string
    {
        if ($this->startDate === null && $this->endDate === null) {
            return $nullPlaceholder;
        }

        if ($this->startDate === null) {
            return $nullPlaceholder . ' - ' . $this->endDate->getLong();
        }

        if ($this->endDate === null) {
            return $this->startDate->getLong() . ' - ' . $nullPlaceholder;
        }

        if ($this->startDate->getLong() === $this->endDate->getLong()) {
            return $this->startDate->getLong();
        }

        if ($this->startDate->getShort() === $this->endDate->getShort()) {
            return $this->startDate->getLong() . ' - ' . $this->endDate->format(DateTime::FORMAT_TIME);
        }

        return $this->startDate->getLong() . ' - ' . $this->endDate->getLong();
    }
}
