<?php

namespace App\Http\Controllers;

use App\Traits\AppTrait;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use AppTrait;
    
    public function index()
    {
        $result = [];
        $items = $this->ReadCSV(storage_path("app/input.csv"));
        $items = $this->ConvertToEuroRates($items);
        $items = $this->addWeekToCommissions($items);
        $weeks = $this->groupByWeek($items);
        foreach($weeks as $week){
            $counter_per_week = 1;
            $sum_week = 0;
            foreach($week as $item){
                switch($item[3]){
                    case 'deposit':
                        array_push($result,$this->Deposit($item[6]));
                        break;
                    case 'withdraw':
                        if($counter_per_week < 4 && $sum_week <= 1000.00){
                            array_push($result,$this->Withdraw($item[6],$item[2]));
                            $counter_per_week++;
                        }else{
                            array_push($result,$this->Withdraw($item[6],'private'));
                        }
                        
                        $sum_week += $item[6];
                        break;
                }
            }
        }
        return response()->json($result);
        // dd($items);
        // dd($this->RoundupNumber(0.023));
    }
}
