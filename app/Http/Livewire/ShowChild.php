<?php

namespace App\Http\Livewire;
use Livewire\WithPagination;

use Livewire\Component;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;

use App\Models\ChildRecord;
class ShowChild extends Component
{
    public $paginate =10;
    use WithPagination;
    public $idku;
    public $detail,$record, $child_records_id;
    public $userID;
    public $isModalOpen = 0;

    public function mount($slug){
        // $this->detail = product::where('slug', $slug)->first();

        $this->detail = Child::where('children.id', $slug)->orderBy('created_at', 'DESC')->get()->first();

        // $this->record = ChildRecord::where('children_id',$slug);
            // ->paginate($this->paginate);
    }

    public function render()
    {
        return view('livewire.show-child');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }
    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }
    public function closeModalPopover()
    {
        $this->isModalOpen = false;
        // return redirect()->to('/data');
    }
    private function resetCreateForm(){
        $this->lingkar = '';
        $this->length = '';
        $this->weight = '';
    }

    public function store()
    {
        $this->UserID = Auth::id();
        $this->validate([
            'lingkar' => 'required',
            'length' => 'required',
            'weight' => 'required',
        ]);

        ChildRecord::updateOrCreate(['id' => $this->child_records_id], [

            'lingkar' => $this->lingkar,
            'length' => $this->length,
            'weight' => $this->weight,
        ]);

        notify()->success($this->children_id ? 'children updated.': 'children created.');
        // session()->flash('message', $this->children_id ? 'children updated.' : 'children created.');
        return redirect()->to('/data');
        $this->closeModalPopover();
        $this->resetCreateForm();
    }
    public function edit($id)
    {
        $children = ChildRecord::findOrFail($id);
        $this->children_id = $id;
        $this->name = $children->name;
        $this->gender = $children->gender;
        $this->birth = $children->birth;

        $this->openModalPopover();
    }

    public function delete($id)
    {
        ChildRecord::find($id)->delete();
        session()->flash('message', 'children deleted.');
    }

    public function show($id)
    {
        $children = ChildRecord::find($id);
        $this->children_id = $id;

        $this->name = $children->name;
        $this->gender = $children->gender;
        $this->birth = $children->birth;
    }

    public function closeModal(){
        $this->isModalOpen = false;
        $this->name = '';
        $this->gender = '';
        $this->birth = '';
        // return redirect()->to('/data');
    }


}
