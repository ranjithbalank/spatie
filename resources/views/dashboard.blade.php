<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @section('content')
        {{-- Left Side Card --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-red-900">
                Hello! <span class="text-success"><b>{{ Auth::user()->name }} 😎</b></span>,
                <br><br>
                Welcome to your <b>MyDMW dashboard!</b>
                <br><br>
                {{ __('You have Successfully logged in!') }}
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-9 gap-6">
                {{-- Right Side Calendar --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3">
                        {{-- <div id="calendar"></div> --}}
                        <a href="{{ route('circulars.index') }}">
                            <h3 class="text-danger fw-bold">Circulars </h3>
                        </a>
                    </div>
                </div>
            @endsection
        </div>
    </div>
</x-app-layout>
