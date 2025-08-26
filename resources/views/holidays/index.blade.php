<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('List of Holidays') }}
            </h2>
            <a href="#" class="text-sm text-red-700 no-underline hover:underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="my-4">


        <div class="py-6">
            {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> --}}
            {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"> --}}
            {{-- Action bar with Create and Search --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                @can('create holidays')
                    <a href="{{ route('holidays.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <span class="mr-1">+</span> Create Holiday
                    </a>
                @endcan

                <form method="GET" action="{{ route('holidays.index') }}"
                    class="flex items-end gap-2 w-full sm:w-1/3">
                    <label for="search" class="sr-only">Search Holidays</label>
                    <input type="text" name="search" placeholder="Search Holidays..."
                        value="{{ request('search') }}"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 ease-in-out" />
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Search
                    </button>
                </form>
            </div>

            {{-- Responsive Table Container --}}
            {{-- <div class="overflow-x-auto rounded-lg shadow-md"> --}}
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12"
                            style="width: 10%">
                            S.No</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12"style="width: 10%">
                            Date</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name</th>
                        @canany(['edit holidays', 'delete holidays'])
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                Action</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($holidays as $index => $holiday)
                        <tr class="hover:bg-gray-100 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('F d, Y - (l)') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ $holiday->name }}
                            </td>
                            @canany(['edit holidays', 'delete holidays'])
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        @can('edit holidays')
                                            <a href="{{ route('holidays.edit', $holiday->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 p-2 rounded-md hover:bg-gray-200 transition duration-150 ease-in-out">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete holidays')
                                            <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Are you sure you want to delete this holiday? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-gray-200 transition duration-150 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No
                                holidays found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
        </div>
        </div>
    </x-slot>
</x-app-layout>
