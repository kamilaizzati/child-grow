<x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 space-between">

    <h2 class="text-center"></h2>
        {{$detail->name}}
    </div>
</x-slot> 

{{-- <x-slot name="header">

</x-slot> --}}

@livewire('child-history', [
    'childrenID' => $detail->id,
    'birth' => $detail->birth,
    'gender' => $detail->gender,
    'name' => $detail->name,
    ])
