<x-app-layout background="bg-white dark:bg-gray-900">
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <div class="max-w-2xl m-auto mt-16">

            <div class="text-center px-4">
                <div class="inline-flex">
                   <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
                    <dotlottie-player src="https://lottie.host/bfacb408-f75d-4d26-8317-4291c5de9099/XZzxrvCJzJ.lottie" background="transparent" speed="1" style="width: 350px; height: 350px" loop autoplay></dotlottie-player>
                </div>
                <div class="mb-6">Hmm...this page doesn't exist. Try searching for something else!</div>
                <a href="{{ route('dashboard') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">Back To Dashboard</a>
            </div>

        </div>

    </div>
</x-app-layout>
