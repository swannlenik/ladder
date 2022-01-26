<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ empty($next) ? __('Duplicate Ladder') : __('Next Ladder') }} <i>"{{ $ladder->name }}"</i>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap">
                        <div class="w-2/12">
                            @include('ladders/ranking')
                        </div>
                        <div class="w-10/12">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('New Ladder Informations') }}
                            </h2>

                            @if ($errors->any())
                                <div class="grid grid-cols-2 gap-4 mt-6">
                                    @error('ladder-name')
                                    <div class="alert-error">{{ $message }}</div>
                                    @endif
                                    @error('ladder-date')
                                    <div class="alert-error">{{ $message }}</div>
                                    @endif
                                    @error('players-list')
                                    <div class="alert-error">{{ $message }}</div>
                                    @endif
                                </div>
                            @endif

                            @include('ladders/rankingForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        @foreach($links as $link)
            <a href="{{ $link['href'] }}" class="{{ $link['class'] ?? 'btn-gray' }} ml-2 mr-2">
                {{ $link['name'] }}
            </a>
        @endforeach
    </x-slot>
</x-app-layout>
