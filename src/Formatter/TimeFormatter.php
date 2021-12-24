<?php declare(strict_types = 1);

namespace Leaditin\Moment\Formatter;

use Leaditin\Moment\Time;

/**
 * Class TimeFormatter
 *
 * @package Leaditin\Moment\Formatter
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class TimeFormatter implements FormatterInterface
{
    /** @var Time */
    private $time;

    /**
     * @param Time $time
     */
    public function __construct(Time $time)
    {
        $this->time = $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getShort() : string
    {
        $hours = (string)$this->time->getHours();
        $minutes = (string)$this->time->getMinutes();

        return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function getLong() : string
    {
        $seconds = (string)$this->time->getSeconds();

        return $this->getShort() . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }
}
