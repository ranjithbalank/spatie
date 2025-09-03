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
    @endsection
    </div>
</x-app-layout>
