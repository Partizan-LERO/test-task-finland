<?php

namespace App\Policy;

/**
 * Class CalendarPolicy
 */
class CalendarPolicy
{
    /**
     * @return array
     */
    public static function getMonths(): array
    {
        return [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];
    }

}
