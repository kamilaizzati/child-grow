<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChildRecord;
use App\Http\Requests\StoreChildRecordRequest;
use App\Http\Requests\UpdateChildRecordRequest;
use Illuminate\Support\Facades\DB;
class HistoryCController extends Controller
{
    
    // public function grafik()
    // {
    //     $total_lingkar = ChildRecord::where(ChildRecord::raw("CAST(SUM(lingkar) as int) as total_lingkar"))
    //     ->GroupBy(ChildRecord::raw("Month(created_ad)"))->pluck('lingkar');


    //     return view('livewire.child-history', compact('lingkar'));
    // }
}
