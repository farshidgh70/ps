<?php

namespace App\Http\Controllers;

use App\Traits\AppTrait;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use AppTrait;
    
    public function index()
    {
        $items = $this->ReadCSV(storage_path("app/input.csv"));
        dd($this->addWeekToCommissions($items));
        dd($this->RoundupNumber(0.023));
    }
}
