<?php

namespace CyberEd\Core\Helpers;

use Carbon\Carbon;
class UtilityHelper
{
    public static function convertYMDTimeToMDYTime($date)
    {

        return Carbon::parse($date)->format('m/d/Y');
    }

    public static function convertMDYToYMD($date){
        return Carbon::parse($date)->format('Y-m-d');
    }

    public static function getCurrentDate(){
        return Carbon::now()->format('Y-m-d');
    
    }
    public static function getYears($date,$year){
        return Carbon::parse($date)->addYear($year)->format('Y-m-d');
    }

    public static function getConvertMDYToYMD($date){
        try {
         
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return '1970-01-01';
        }

        
    }
}