<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Players') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('set.available.players') }}">
                        @csrf

                        <div class="grid grid-cols-4 gap-4 w-full">
                            @foreach($players as $player)
                                <div class="flex flex-row border">
                                    <div class="w-auto p-2 text-center">
                                        <input type="checkbox" name="available-player[{{$player->id}}]" {{ $player->available === 1 ? 'checked="checked"' : '' }} />
                                    </div>
                                    <div class="grow p-2 text-left">
                                        {{$player->name}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="w-full text-center">
                            <input type="submit" class="btn-green" value="{{ __('Set players as Available') }}" />
                        </div>
                    </form>
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
