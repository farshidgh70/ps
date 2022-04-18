<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

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

    public function ConvertToEuroRates($items)
    {
        $fetchedRates = Http::get("https://developers.paysera.com/tasks/api/currency-exchange-rates")->json(); // for speed up we can store these data
        for($i=0; $i<count($items); $i++){
            $rate = $fetchedRates["rates"][$items[$i][5]];
            $amount = $items[$i][4];
            if($items[$i][5] != 'EUR'){
                $amount = $items[$i][4]/$rate;
            }
            $items[$i][6] = $this->RoundupNumber($amount);
        }
        return $items;
    }

    public function addWeekToCommissions($items)
    {
        for($i=0; $i<count($items); $i++){
            $items[$i][7] = Carbon::parse($items[$i][0])->format("Y-W");
        }
        return $items;
    }

    public function groupByWeek($items)
    {
        $col = collect($items);
        return $col->groupBy(7);
    }

    public function Deposit($amount)
    {
        return number_format(0.03 * $amount/100, 2, '.','');
    }

    public function Withdraw($amount, $type)
    {
        $result = null;
        switch($type){
            case 'private':
                $result = 0.3*$amount/100;
                break;
            case 'business':
                $result = 0.5*$amount/100;
                break;
        }
        return number_format($result, 2, '.', '');
    }

    public function RoundupNumber($num)
    {
        $temp = $num * 1000;
        $temp_2 = $num * 100;
        $_3rd_place_decimal = $temp % 10;
        if($_3rd_place_decimal > 0){
            $temp_2++;
        }
        return number_format(floor($temp_2)/100,2, '.', '');
    }
}