<?php declare(strict_types = 1);

namespace Leaditin\Moment\Formatter;

use Leaditin\Moment\DateTime;

/**
 * Class DateTimeFormatter
 *
 * @package Leaditin\Moment\Formatter
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class DateTimeFormatter implements FormatterInterface
{
    /** @var DateTime */
    private $dateTime;

    /**
     * @param DateTime $dateTime
     */
    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getShort() : string
    {
        return $this->dateTime->format(DateTime::FORMAT_SHORT);
    }

    /**
     * {@inheritdoc}
     */
    public function getLong() : string
    {
        $format = DateTime::FORMAT_SHORT;
        if ($this->dateTime->getDateType() === DateTime::TYPE_DATETIME) {
            $format .= ', ' . DateTime::FORMAT_TIME;
        }

        return $this->dateTime->format($format);
    }
}
