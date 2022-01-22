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
                    <div class="grid grid-cols-4 gap-4 w-full">
                        @foreach($players as $player)
                            <div class="flex flex-row border">
                                <div class="w-auto p-2 text-center">{{$player->id}}</div>
                                @if ($player->deletable)
                                    <div class="grow p-2 text-left">
                                        {{$player->name}}
                                    </div>
                                    <div class="w-auto p-2 mr-1 text-left">
                                        <a href="{{ route('delete.player', ['playerID' => $player->id]) }}" class="btn-red">{{ __('Delete') }}</a>
                                    </div>
                                @else
                                    <div class="grow p-2 text-left">
                                        {{$player->name}}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if (isset($accessRights['create.player']) && $accessRights['create.player'] === 'RW')
                    <div class="w-full p-2 mt-6">
                        <form class="players-list__form" method="POST" action="{{ route('create.player') }}">
                            @csrf
                            <div class="w-full">
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                    {{ __('Create new player') }}
                                </h2>
                            </div>
                            <div class="w-full">
                                <input type="text" name="player-name" value="" placeholder="{{ __('Player Name') }}" maxlength="64" class="w-1/4"/>
                            </div>
                            <div class="w-full mt-2">
                                <input type="submit" name="player-submit" value="{{ __('Create new player') }}" class="btn-green"/>
                            </div>
                        </form>
                    </div>
                    @endif

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
