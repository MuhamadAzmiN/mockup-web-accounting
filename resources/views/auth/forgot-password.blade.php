<x-authentication-layout>
    {{-- <h1 class="text-3xl text-gray-800 dark:text-gray-100 font-bold mb-6">{{ __('Reset your Password') }}</h1>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif
    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <x-label for="email">{{ __('Email Address') }} <span class="text-red-500">*</span></x-label>
            <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />                
        </div>
        <div class="flex justify-end mt-6">
            <x-button>
                {{ __('Send Reset Link') }}
            </x-button>
        </div>
    </form> --}}
     <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="max-w-2xl m-auto mt-16">
            <div class="text-center px-4">
                <div class="inline-flex mb-5">
                    {{-- Lottie Animation --}}
                    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
                    <dotlottie-player src="https://lottie.host/9c292f93-8bb3-4d0d-99f3-9e15460f7eea/3QGo6JG87g.lottie" background="transparent" speed="1" style="width: 250px; height: 250px" loop autoplay>
                    </dotlottie-player>
                </div>

                <div class="mb-6 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    We’re performing scheduled maintenance.<br>
                    Thank you for your patience and understanding.
                </div>

                <a href="{{ route('dashboard') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    Back To Dashboard
                </a>
            </div>
        </div>
    </div>
    <x-validation-errors class="mt-4" /> 
</x-authentication-layout>
