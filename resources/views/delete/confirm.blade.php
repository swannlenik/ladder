<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Are you sure?') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <a href="{{ $previous }}" class="btn btn-red">
                    {{ __('Cancel deletion') }}
                </a>
                <a href="{{ $route }}" class="btn btn-green">
                    {{ __('Confirm') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
