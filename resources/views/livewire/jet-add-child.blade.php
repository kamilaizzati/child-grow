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
                    <x-jet-label for="name" value="{{ __('Nama Lengkap') }}"/>
                    <x-jet-input
                        wire:model="name"
                        class="block mt-1 w-full"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required="required"
                        autofocus="autofocus"
                        autocomplete="name"/>

                    <x-jet-label for="name" value="{{ __('Jenis Kelamin') }}"/>
                    <select
                        wire:model="gender"
                        id="gender"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option >Jenis Kelamin</option>
                        <option >Laki-Laki</option>
                        <option >Perempuan</option>
                    </select>

                    <x-jet-label for="name" value="{{ __('Tanggal Lahir') }}"/>
                    <input
                    wire:model="birth"
                        type="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        name="birthday">

                        <div class="flex mt-4">
                            <x-jet-button wire:click.prevent="store()">
                                @if ($id = $this->children_id)
                                {{ __('Simpan') }}
                                @else
                                {{ __('Tambah') }}
                                @endif
                            </x-jet-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
