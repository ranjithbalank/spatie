<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-red-900 ">
                    Hello!  <span class="text-success"> <b>{{Auth::user()->name }}😎</b>,</span>
                    <br><br>Welcome to your <b>MyDMW dashboard!<b>
                        <br><br>
                    {{ __("You have Successfully logged in!") }}
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
