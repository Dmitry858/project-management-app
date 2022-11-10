<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <h1 class="text-2xl sm:text-3xl">@lang('titles.app_name')</h1>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register', ['key' => $key]) }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('form.label_name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('form.label_email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('form.label_password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('form.label_confirm_password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex flex-wrap items-center justify-center sm:justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('form.already_registered') }}
                </a>

                <x-button class="ml-2 mr-2 sm:ml-4 sm:mr-0 my-2 bg-blue-600 hover:bg-blue-700">
                    {{ __('buttons.register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
