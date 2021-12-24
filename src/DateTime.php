<?php declare(strict_types = 1);

namespace Leaditin\Moment;

use DateTimeZone;
use Leaditin\Moment\Formatter\DateTimeFormatter;

/**
 * Class DateTime
 *
 * @package Leaditin\Moment
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateTime extends \DateTime
{
    public const ISO_8601_NO_TIMEZONE = 'Y-m-d H:i:s';
    public const ISO_8601_DATE_ONLY = 'Y-m-d';

    public const FORMAT_LONG = 'd F Y';
    public const FORMAT_LONG_DAY_OF_WEEK = 'l, d F Y';
    public const FORMAT_SHORT = 'd M Y';
    public const FORMAT_SHORT_DAY_OF_WEEK = 'D, d M Y';
    public const FORMAT_TIME = 'H:i';

    public const TYPE_DATETIME = 'datetime';
    public const TYPE_DATE = 'date';

    protected $dateType;

    /** @var DateTimeFormatter */
    private $formatter;

    /**
     * @param string $time
     * @param DateTimeZone $timezone
     * @param string $dateType
     */
    public function __construct(string $time = 'now', DateTimeZone $timezone = null, string $dateType = self::TYPE_DATETIME)
    {
        parent::__construct($time, $timezone);

        $this->setDateType($dateType);
        $this->formatter = new DateTimeFormatter($this);
    }

    /**
     * @return string
     */
    public function getDateType() : string
    {
        return $this->dateType;
    }

    /**
     * @param $dateType
     *
     * @return DateTime
     */
    public function setDateType($dateType) : DateTime
    {
        if ($dateType === self::TYPE_DATE) {
            $this->setTime(0, 0);
        }

        $this->dateType = $dateType;

        return $this;
    }

    /**
     * @return string
     */
    public function getShort() : string
    {
        return $this->formatter->getShort();
    }

    /**
     * @return string
     */
    public function getLong() : string
    {
        return $this->formatter->getLong();
    }

    /**
     * @return string
     */
    public function getUnixTimestamp() : string
    {
        if ($this->dateType === self::TYPE_DATE) {
            return $this->format(self::ISO_8601_DATE_ONLY);
        }

        return $this->format(self::ISO_8601_NO_TIMEZONE);
    }

    /**
     * @return DateTime
     */
    public function getGmtDateTime() : DateTime
    {
        $gmtDate = clone $this;
        $gmtDate->setTimezone(new DateTimeZone('Europe/London'));

        return $gmtDate;
    }

    /**
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function isEqualTo(DateTime $dateTime) : bool
    {
        return $this->getTimestamp() === $dateTime->getTimestamp();
    }

    /**
     * @param DateTime $date
     *
     * @return bool
     */
    public function isBefore(DateTime $date) : bool
    {
        return $this->getTimestamp() < $date->getTimestamp();
    }

    /**
     * @return bool
     */
    public function isToday() : bool
    {
        $today = new self('now', $this->getTimezone(), self::TYPE_DATE);

        return $this->getShort() === $today->getShort();
    }

    /**
     * @param DateTime $date
     *
     * @return bool
     */
    public function isAfter(DateTime $date) : bool
    {
        return $this->getTimestamp() > $date->getTimestamp();
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getLong();
    }
}
