<?php declare(strict_types = 1);

namespace Leaditin\Moment;

use Leaditin\Moment\Exception\InvalidArgumentException;
use Leaditin\Moment\Formatter\TimeFormatter;

/**
 * Class Time
 *
 * @package Leaditin\Moment
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
class Time
{
    /** @var int */
    private $seconds;

    /** @var TimeFormatter */
    private $formatter;

    /**
     * @param int $seconds
     */
    private function __construct(int $seconds)
    {
        $this->seconds = $seconds;
        $this->formatter = new TimeFormatter($this);
    }

    /**
     * @param int $seconds
     *
     * @return Time
     */
    public static function fromSeconds(int $seconds) : Time
    {
        return new self($seconds);
    }

    /**
     * @param string $time
     *
     * @throws InvalidArgumentException
     *
     * @return Time
     */
    public static function fromString(string $time) : Time
    {
        if (preg_match('/^(\d+):(\d{2})$/', $time, $matches)) {
            $seconds = ($matches[1] * 3600) + ($matches[2] * 60);
        } elseif (preg_match('/^(\d+):(\d{2}):(\d{2})$/', $time, $matches)) {
            $seconds = ($matches[1] * 3600) + ($matches[2] * 60) + $matches[3];
        } else {
            throw InvalidArgumentException::invalidTimeFormat($time);
        }

        return new self($seconds);
    }

    /**
     * @return int
     */
    public function getHours() : int
    {
        return (int)floor($this->seconds / 3600);
    }

    /**
     * @return int
     */
    public function getMinutes() : int
    {
        return (int)floor(($this->seconds - ($this->getHours() * 3600)) / 60);
    }

    /**
     * @return int
     */
    public function getSeconds() : int
    {
        return $this->seconds - ($this->getHours() * 3600) - ($this->getMinutes() * 60);
    }

    /**
     * @return int
     */
    public function getTotalHours() : int
    {
        return (int)floor($this->seconds / 3600);
    }

    /**
     * @return int
     */
    public function getTotalMinutes() : int
    {
        return (int)floor($this->seconds / 60);
    }

    /**
     * @return int
     */
    public function getTotalSeconds() : int
    {
        return $this->seconds;
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
     * @param Time $time
     * @return Time
     */
    public function addTime(Time $time) : Time
    {
        $this->seconds += $time->getTotalSeconds();

        return $this;
    }

    /**
     * @param Time $time
     * @return Time
     */
    public function subTime(Time $time) : Time
    {
        $this->seconds -= $time->getTotalSeconds();

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getShort();
    }
}
