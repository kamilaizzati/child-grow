<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Aplikasi Monitoring pertumbuhan anak') }}
    </h2>
</x-slot>
<div class="py-5">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
            <div
                class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                role="alert">
                <div class="flex">
                    <div>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="px-4">

                @if($isModalOpen)
                <button
                    wire:click="closeModal()"
                    class="mt-4 inline-flex justify-center w-full rounded-md border border-transparent py-2 bg-red-600 text-base font-bold text-white shadow-sm hover:bg-red-700">
                    Batal
                </button>
                @include('livewire.jet-add-child') @else
                <button
                    wire:click="create()"
                    class="mt-4 inline-flex justify-center w-full rounded-md border border-transparent py-2 bg-blue-500 text-base font-bold text-white shadow-sm hover:bg-blue-600">
                    Tambah Anak
                </button>
                @endif
            </div>
            <div class="w-full block sm:flex p-2">
                <div class="flex w-full justify-start">
                    <div class="flex w-full sm:w-1/2 md:2-1/3 p-2">
                        {{-- PAGINATION --}}
                        <select
                            wire:model="paginate"
                            class="form-control flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border-b-4 border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="">All</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center text-gray-700">
                            <svg
                                class="fill-current h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"></svg>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-end">
                    <div class="flex w-full sm:w-1/2 md:2-1/3 p-2">
                        {{-- SEARCH --}}
                        <input
                            type="search"
                            class="form-control flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border-b-4 border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                            placeholder="Search"
                            aria-label="Search"
                            wire:model="srcItem"
                            aria-describedby="button-addon2"></div>
                    </div>
                </div>

                <div class="sm:flex block px-2">

                    <table class="table-fixed hover w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Nama Lengkap</th>
                                <th class="px-4 py-2">Jenis Kelamin</th>
                                <th class="px-4 py-2">Usia</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($children as $child) 
                            @php 
                            $dateOfBirth = $child->birth; 
                            
                            $dateTahun = \Carbon\Carbon::parse($dateOfBirth)->diff(\Carbon\Carbon::now())->format('%y');
                            
                            $dateBulan = \Carbon\Carbon::parse($dateOfBirth)->diff(\Carbon\Carbon::now())->format('%m');
                            $dateHari = \Carbon\Carbon::parse($dateOfBirth)->diff(\Carbon\Carbon::now())->format('%d');
                            
                            if ($dateTahun == 0 && $dateBulan != 0) { $tglLahir = $dateBulan . ' Bulan,
                            '.$dateHari.' Hari'; }elseif ($dateTahun == 0 && $dateBulan == 0) { $tglLahir =
                            $dateHari.' Hari'; }elseif ($dateBulan == 0) { $tglLahir = $dateTahun . ' Tahun,
                            '.$dateHari.' Hari'; }elseif ($dateHari == 0 && $dateBulan == 0) { $tglLahir =
                            $dateTahun . ' Tahun'; }else { $tglLahir = $dateTahun . ' Tahun, '.$dateBulan .
                            ' Bulan, '.$dateHari.' Hari'; } @endphp
                            <tr>
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $child->name }}</td>
                                <td class="border px-4 py-2">{{ $child->gender}}</td>
                                <td class="border px-4 py-2">{{ $tglLahir }}</td>
                                <td
                                    class="px-2 py-2 md:px-5 md:py-5 md:text-lg text-md border-b border-gray-200 bg-white">
                                    <div class="flex justify-around">

                                        {{-- show details --}}
                                        <a href="/show/{{$child->id}}">
                                            <button class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded mx-0">
                                                <svg
                                                    class="w-5 h-5"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </a>
                                        {{-- edit --}}
                                        <button
                                            wire:click="edit({{ $child->id }})"
                                            class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded mx-0">
                                            <svg
                                                class="w-5 h-5"
                                                fill="black"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </button>
                                        {{-- delete --}}
                                        <button
                                            wire:click="delete({{ $child->id }})"
                                            class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded mx-0">
                                            <svg
                                                class="w-5 h-5"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        {{-- modal delete --}}

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="border px-4 py-2 text-center text-md font-bold" colspan="5">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    <div class="px-10 pt-2">{{ $children->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
