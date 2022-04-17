<?php

namespace App\Traits;

use Carbon\Carbon;

trait AppTrait{

    public function ReadCSV($input)
    {
        $items = [];
        if (($open = fopen($input, "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $items[] = $data;
            }
        
            fclose($open);
        }
        return $items;
    }

    public function addWeekToCommissions($items)
    {
        for($i=0; $i<count($items); $i++){
            $items[$i][6] = Carbon::parse($items[$i][0])->weekOfYear;
        }
        return $items;
    }

    public function Deposit($amount)
    {
        return 0.03 * $amount/100;
    }

    public function Withdraw($amount, $type)
    {
        $result = null;
        switch($type){
            case 'private':
                //
                break;
            case 'business':
                $result = $amount - (0.5 * $amount/100);
                break;
        }
        return $result;
    }

    public function RoundupNumber($num)
    {
        $temp = $num * 1000;
        $temp_2 = $num * 100;
        $_3rd_place_decimal = $temp % 10;
        if($_3rd_place_decimal > 0){
            $temp_2++;
        }
        return number_format($temp_2/100,2);
    }
}