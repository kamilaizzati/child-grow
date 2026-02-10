<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Add New Task') }}
    </h2>
</x-slot>

<div class="py-5">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg px-4 py-4">
                <x-jet-validation-errors class="mb-4"/>

                <div>
                    <x-jet-label for="lingkar" value="{{ __('Lingkar (mm)') }}"/>
                    <x-jet-input
                        wire:model="lingkar"
                        class="block mt-1 w-full"
                        type="text"
                        name="lingkar"
                        :value="old('lingkar')"
                        required="required"
                        autofocus="autofocus"
                        autocomplete="lingkar"/>

                    <x-jet-label for="panjang" value="{{ __('Jenis Kelamin') }}"/>
                    <x-jet-input
                    wire:model="length"
                    class="block mt-1 w-full"
                    type="text"
                    name="length"
                    :value="old('length')"
                    required="required"
                    autofocus="autofocus"
                    autocomplete="length"/>

                    <x-jet-label for="weight" value="{{ __('Berat (kg)') }}"/>
                    <x-jet-input
                    wire:model="weight"
                    class="block mt-1 w-full"
                    type="text"
                    name="weight"
                    :value="old('weight')"
                    required="required"
                    autofocus="autofocus"
                    autocomplete="weight"/>

                        <div class="flex mt-4">
                            <x-jet-button wire:click.prevent="store()">
                                {{-- @if ($id = $this->children_id)
                                {{ __('Simpan') }}
                                @else
                                {{ __('Tambah') }}
                                @endif --}}
                                {{ __('Tambah') }}
                            </x-jet-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
