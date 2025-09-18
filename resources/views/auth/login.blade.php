<x-guest-layout>
    <!-- Session Status -->


    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div>
            <h5 class="text-lg text-center font-medium text-gray-900 relative">
                {{ __('Let’s Get Started — Log in!') }}
                <a href="https://drive.google.com/file/d/1bVawXfliVMwKE9VlQ2SXpJ0OL8XMaBJ_/view?usp=sharing"
                    class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 m-2" viewBox="0 0 20 20" fill="red">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-2 4a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 00-1-1h-2a1 1 0 00-1 1z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </h5>
            <hr class="my-4 border-gray-300 w-full">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
            </a>
            @endif --}}

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>


</x-guest-layout>