<?php declare(strict_types=1);

namespace Leaditin\Moment;

use DateInterval;
use Leaditin\Moment\Comparator\DateTimeComparator;
use Leaditin\Moment\Exception\InvalidArgumentException;
use Leaditin\Moment\Formatter\DateRangeFormatter;

/**
 * Class DateRange
 *
 * @package Leaditin\Moment
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateRange
{
    /** @var DateTime */
    private $startDate;

    /** @var DateTime */
    private $endDate;

    /** @var DateRangeFormatter */
    private $formatter;

    /**
     * @param DateTime|null $startDate
     * @param DateTime|null $endDate
     *
     * @throws InvalidArgumentException
     */
    public function __construct(DateTime $startDate = null, DateTime $endDate = null)
    {
        $this->startDate = $startDate ? clone $startDate : null;
        $this->endDate = $endDate ? clone $endDate : null;

        if ($startDate && $endDate && $endDate < $startDate) {
            throw InvalidArgumentException::startDateNotBeforeEndDate($startDate, $endDate);
        }

        $this->formatter = new DateRangeFormatter($this);
    }

    /**
     * @return null|DateTime
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @return null|DateTime
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $date
     *
     * @return bool
     */
    public function containsDate(DateTime $date): bool
    {
        if ($this->startDate === null) {
            return $this->endDate === null || $this->endDate >= $date;
        }

        if ($this->endDate === null) {
            return $this->startDate <= $date;
        }

        return $this->startDate <= $date && $this->endDate >= $date;
    }

    /**
     * @param DateRange $dateRange
     *
     * @return bool
     */
    public function containsDateRange(DateRange $dateRange): bool
    {
        if ($this->startDate === null && $this->endDate === null) {
            return true;
        }

        return $this->startDate <= $dateRange->getStartDate()
            && $dateRange->getEndDate() !== null
            && $this->endDate >= $dateRange->getEndDate();
    }

    /**
     * @param DateRange $dateRange
     *
     * @return bool
     */
    public function overlapsDateRange(DateRange $dateRange): bool
    {
        return $this->intersect($dateRange) !== null;
    }

    /**
     * @param DateTime $date
     *
     * @return bool
     */
    public function isBefore(DateTime $date): bool
    {
        if ($this->endDate === null) {
            return false;
        }

        return $this->endDate <= $date;
    }

    /**
     * @return bool
     */
    public function isNow(): bool
    {
        return $this->containsDate(new DateTime());
    }

    /**
     * @param DateTime $date
     *
     * @return bool
     */
    public function isAfter(DateTime $date): bool
    {
        if ($this->startDate === null) {
            return false;
        }

        return $this->startDate >= $date;
    }

    /**
     * @param DateRange $dateRange
     *
     * @return null|DateRange
     */
    public function intersect(DateRange $dateRange): ?DateRange
    {
        $latestStartDate = DateTimeComparator::getLatestDate($this->startDate, $dateRange->startDate);
        $earliestEndDate = DateTimeComparator::getEarliestDate($this->endDate, $dateRange->endDate);

        if ($latestStartDate && $earliestEndDate && ($latestStartDate >= $earliestEndDate)) {
            return null;
        }

        return new DateRange($latestStartDate, $earliestEndDate);
    }

    /**
     * @param string $nullPlaceholder
     *
     * @return string
     */
    public function getShort(string $nullPlaceholder = 'Ongoing'): string
    {
        return $this->formatter->getShort($nullPlaceholder);
    }

    /**
     * @param string $nullPlaceholder
     *
     * @return string
     */
    public function getLong(string $nullPlaceholder = 'Ongoing'): string
    {
        return $this->formatter->getLong($nullPlaceholder);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return DateTime[]
     *
     */
    public function getAllDates(): array
    {
        if ($this->getStartDate() === null || $this->getEndDate() === null) {
            throw InvalidArgumentException::dateRangeWithoutBoundaries();
        }

        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $referenceDate = clone $startDate;
        $referenceDate->setDateType(DateTime::TYPE_DATE);

        $dates = [];
        while ($referenceDate <= $endDate) {
            $dates[$referenceDate->getUnixTimestamp()] = clone $referenceDate;
            $referenceDate->add(new DateInterval('P1D'));
        }

        return array_values($dates);
    }

    /**
     * @return null|Time
     */
    public function getDuration(): ?Time
    {
        if ($this->startDate && $this->endDate) {
            return Time::fromSeconds($this->endDate->getTimestamp() - $this->startDate->getTimestamp());
        }

        return null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getLong();
    }

    public function __clone()
    {
        $this->startDate = ($this->startDate ? clone $this->startDate : null);
        $this->endDate = ($this->endDate ? clone $this->endDate : null);
    }
}
