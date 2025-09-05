<section>
    <header naemme="header" class="mb-4">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('My Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="emp_id" :value="__('Employee Id')" />
            <x-text-input id="emp_id" name="emp_id" type="text" class="mt-1 block w-25" :value="old('name', $user->employees->emp_id)"
                readonly />
            <x-input-error class="mt-2" :messages="$errors->get('emp_id')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                readonly />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        {{-- @dd($user->employees->manager->emp_name ?? 'No Manager'); --}}
        <div>
            @if ($user->employees->manager_id != null)
                <x-input-label for="manager" :value="__('Manager Name')" />
                <x-text-input id="manager" name="manager" type="text" class="mt-1 block w-full" :value="old('manager', $user->employees->manager->emp_name ?? 'No Manager')"
                    readonly />
                @error('manager_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            @endif
        </div>



        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
