<?php

namespace TMG\UtilitiesBundle\Validators;

class DateValidator
{
    /**
     * Take in a string and an expected format and creates a DateTime object.
     * If the resulting object doesn't match the provided string, return false.
     *
     * @param string $string
     * @param string $format
     *
     * @return \DateTime|bool False the string couldn't be parsed correctly.
     */
    public static function validate($string, $format = 'Y-m-d')
    {
        $date = \DateTime::createFromFormat($format, $string);

        if (!($date && $date->format($format) === $string)) {
            return false;
        }

        return $date;
    }
}
