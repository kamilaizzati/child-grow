<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;

// use Carbon\Carbon;
use App\Models\Child;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class Dashboard extends Component
{
    // pagination
    public $paginate =10;
    use WithPagination;

    public $srcItem;
    protected $queryString = ['srcItem'];

    public $name, $gender, $birth, $children_id;
    public $userID;
    public $isModalOpen = 0;

    public function render()
    {
        $srcItem = '%'.$this->srcItem.'%';
        $this->UserID = Auth::id();
        $this->user = User::all();

        return view('livewire.dashboard',[
            // 'children' => Child::find(Auth::id())
            // ->Where('name','LIKE',$srcItem)
            // ->orWhere('gender','LIKE',$srcItem)
            // ->orderBy('created_at', 'DESC')->paginate($this->paginate),

            'children' => Child::where('user_id',Auth::id())
            ->Where('name','LIKE',$srcItem)
            ->paginate($this->paginate)
        ]);


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
        $this->name = '';
        $this->gender = '';
        $this->birth = '';
    }

    public function store()
    {
        $this->UserID = Auth::id();
        $this->validate([
            'name' => 'required',
            'gender' => 'required',
            'birth' => 'required',
        ]);

        Child::updateOrCreate(['id' => $this->children_id], [

            'name' => $this->name,
            'gender' => $this->gender,
            'birth' => $this->birth,
            'user_id' => Auth::id(),
        ]);
        notify()->success($this->children_id ? 'children updated.': 'children created.');

        return redirect()->to('/data');
        $this->closeModalPopover();
        $this->resetCreateForm();
    }
    public function edit($id)
    {
        $children = Child::findOrFail($id);
        $this->children_id = $id;
        $this->name = $children->name;
        $this->gender = $children->gender;
        $this->birth = $children->birth;

        $this->openModalPopover();
    }

    public function delete($id)
    {
        Child::find($id)->delete();
    }

    public function show($id)
    {
        $children = Child::find($id);
        $this->children_id = $id;

        // $this->subcategories_id = $produk->subcategories_id;
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
