<?php declare(strict_types = 1);

namespace Leaditin\Moment\Formatter;

/**
 * Interface FormatterInterface
 *
 * @package Leaditin\Moment\Formatter
 * @author Igor Vuckovic <igor@vuckovic.biz>
 */
interface FormatterInterface
{
    /**
     * @return string
     */
    public function getShort() : string;

    /**
     * @return string
     */
    public function getLong() : string;
}
