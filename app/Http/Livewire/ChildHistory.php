<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\ChildRecord;
use \App\Http\Livewire\ShowChild;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use \App\Charts\childCharts;

class ChildHistory extends Component
{
    public $childrenID;
    public $birth;
    public $gender;
    public $name;
    public function mount($childrenID, $birth, $gender, $name)
    {
        $this->history = ChildRecord::where('children_id', $childrenID)->orderBy('created_at', 'ASC')->get();
        // $price = [];

        // foreach($history['historical'] as $stockPrice){
        //     $price [] = $stockPrice['close'];
        // }
    }

    public function render(childCharts $chart)
    {
        return view('livewire.child-history', ['chart' => $chart->build()]);
    }

    // public function render()
    // {
    //     // $total_lingkar = ChildRecord::where(ChildRecord::raw("CAST(SUM(lingkar) as int) as lingkar"));

    //     // ->GroupBy(ChildRecord::raw("Month(created_ad)"))->pluck('lingkar');

    //     // $filtered_items = \DB::table('items')
    //     // ->selectRaw('SUM(item_quantity) as sum_quantity, SUM(item_value) as sum_value')
    //     // ->whereIn('item_color', [ /* array controlled by checkboxes values in view */ ])
    //     // ->first();

    //     $total_lingkar = array_sum($this->lingkar);


    //     return view('livewire.child-history', compact('total_lingkar'));
    // }

    public function store($slug,$lingkar,$panjang,$berat)
    {
        $this->UserID = $slug;
        // $this->validate([
        //     'lingkar' => 'required',
        //     'length' => 'required',
        //     'weight' => 'required',
        // ]);

        // ChildRecord::updateOrCreate(['children_id' => $slug], [

        //     'lingkar' => $lingkar,
        //     'length' => $panjang,
        //     'weight' => $berat,
        //     'children_id' => $slug,
        // ]);
        ChildRecord::Create([

            'lingkar' => $lingkar,
            'length' => $panjang,
            'weight' => $berat,
            'children_id' => $slug,
        ]);

        // session()->flash('message', $this->children_id ? 'children updated.' : 'children created.');
        // return redirect()->to('/show/.$slug.');
        // return redirect()->action([ShowChild::class, 'render']);
        return redirect()->back();
        // return 'oke';

    }

}
