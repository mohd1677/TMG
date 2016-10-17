<?php

namespace TMG\Api\UtilityBundle\Date;

class DateUtility
{
    /**
     * Returns a formatted date range.
     *
     * @param $range
     * @param $returnObject
     * @return object
     */
    public static function getReputationDateRange($range)
    {
        $rangeResult = [];
        $start = new \DateTime('now');
        switch ($range) {
            case 30:
                $start->sub(new \DateInterval('P1M'));
                break;
            case 60:
                $start->sub(new \DateInterval('P2M'));
                break;
            case 90:
                $start->sub(new \DateInterval('P3M'));
                break;
            case 180:
                $start->sub(new \DateInterval('P6M'));
                break;
            case 365:
                $start->sub(new \DateInterval('P1Y'));
                break;
            case 'all':
                $start = null;
                break;
            default:
                $range = 365;
                $start->sub(new \DateInterval('P1Y'));
                break;
        }

        $end = new \DateTime('now');
        $end = $end->format('Y-m-d');
        $start = $start->format('Y-m-d');

        $rangeResult['end'] = $end;
        $rangeResult['range'] = $range;
        $rangeResult['start'] = $start;

        return $rangeResult;
    }
}
