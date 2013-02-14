<?php

namespace Stamp;

class Stamp
{
    /**
     * @var array
     */
    private $cache = array();

    /**
     * @param $example
     * @param $time
     * @return string
     * @throws \Exception
     */
    public function stamp($example, $time)
    {

        $translator = $this->fetchTranslator($example);

        // what is our argument?
        if (!is_numeric($time)) {
            if (is_string($time)) {
                $time = strtotime($time);
            } elseif (is_object($time) && get_class($time) == 'DateTime') {
                $time = $time->getTimestamp();
            } else {
                throw new \Exception('Invalid date');
            }
        }


        return $translator->translate($time);
    }

    /**
     * @param $example
     * @return Translator
     */
    private function fetchTranslator($example)
    {
        if (isset($this->cache[$example])) {
            return $this->cache[$example];
        }

        $translator = new Translator($example);
        $this->cache[$example] = $translator;
        return $translator;
    }
}
