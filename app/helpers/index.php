<?php

use Carbon\Carbon;

if (!function_exists('getTime')) {
    function getTime($date)
    {
        $d1 = Carbon::parse($date);
        $d2 = Carbon::now();

        $diffInMinutes = $d1->diffInMinutes($d2); //23
        $diffInHours   = $d1->diffInHours($d2); //8
        $diffInDays    = $d1->diffInDays($d2); //21
        $diffInMonths  = $d1->diffInMonths($d2); //4
        $diffInYears   = $d1->diffInYears($d2); //1

        if ($diffInYears >= 1) {
            return addSIfMany($diffInYears, 'year');
        } elseif ($diffInMonths >= 1) {
            return addSIfMany($diffInMonths, 'month');
        } elseif ($diffInDays >= 1) {
            return addSIfMany($diffInDays, 'day');
        } elseif ($diffInHours >= 1) {
            return addSIfMany($diffInHours, 'hour');
        } elseif ($diffInMinutes >= 1) {
            return addSIfMany($diffInMinutes, 'minute');
        } else {
            return 'now';
        }
    }
}
if (!function_exists('addSIfMany')) {

    function addSIfMany($number, $string)
    {
        if ($number == 1) {
            return $number . ' ' . $string .  ' ago';
        }
        return $number . ' ' . $string . 's' .  ' ago';
    }
}
