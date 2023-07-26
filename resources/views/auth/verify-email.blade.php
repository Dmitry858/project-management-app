<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <h1 class="text-2xl sm:text-3xl">@lang('titles.app_name')</h1>
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.verify_email') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('auth.new_verification_link') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        {{ __('buttons.resend_email') }}
                    </x-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('buttons.logout') }}
                </button>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>
