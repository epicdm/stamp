<?php
/*
 * Responsible for looking up and formatting date parts
 */

namespace Stamp\Emitters;

class TwoDigitDate extends BaseEmitter implements IBaseEmitter
{
    private $value;
    private $previous;
    public $field;

    /**
     * @param $value
     * @param $previous
     */
    public function __construct($value, $previous)
    {
        $this->value = intval($value);
        $this->previous = $previous;
    }

    /**
     * @param $time
     * @return string
     */
    public function format($time)
    {
        // do some magic and determine if we are looking for month, date or year
        if ($this->value >= 60 && $this->value <= 99) {
            return $this->formatYear($time);
        } elseif ($this->value == 12 && $this->previous && $this->previous->field != 'month') {
            return $this->formatMonth($time);
        } elseif ($this->value >= 13 && $this->value <= 31) {
            return $this->formatDay($time);
        } else {
            // see if we can figure it out by context
            if ($this->previous) {
                switch ($this->previous->field) {
                    case 'month':
                        return $this->formatDay($time);
                    case 'day':
                        return $this->formatYear($time);
                    case 'year':
                        return $this->formatMonth($time);
                }
            }
            // give up and go with month
            return $this->formatMonth($time);
        }
    }

    /**
     * @param $time
     * @return string
     */
    private function formatYear($time)
    {
        $this->field = 'year';

        return date("y", $time);
    }

    /**
     * @param $time
     * @return string
     */
    private function formatDay($time)
    {
        $this->field = 'day';

        return date("d", $time);
    }

    /**
     * @param $time
     * @return string
     */
    private function formatMonth($time)
    {
        $this->field = 'month';

        return date("m", $time);
    }
}
