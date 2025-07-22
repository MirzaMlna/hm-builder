<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <button class="bg-primary text-white px-4 py-2 rounded hover:bg-secondary">
                    Submit
                </button>

                <p class="text-secondary mt-4">
                    Ini warna teks secondary
                </p>

            </div>
        </div>
    </div>
</x-app-layout>
