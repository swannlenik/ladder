<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Multiple Groups for ladder') }} {{ $ladder->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="w-full mt-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ count($players) }} {{ __('Players available') }}</h2>
                    </div>

                    <form method="POST" action="{{ route('save.groups') }}">
                        @csrf
                        <input type="hidden" name="ladder-id" value="{{ $ladder->id }}">

                        <div class="grid grid-cols-4 gap-4">
                            @foreach ($players as $player)
                                <div class="flex flex-row py-2">
                                    <div class="w-1/3 text-right pr-2">
                                        {{ $player->name }}
                                    </div>
                                    <div class="w-2/3">
                                        <input type="text" name="player[{{ $player->id }}]" value="" placeholder="Group Name" class="w-full"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="w-full text-center py-2">
                            <input type="submit" value="Create Groups" class="btn-green">
                        </div>
                    </form>

                    <div class="message-yellow my-6">
                        Example: To create a group named "Group A", type the same group name ("Group A") in the textbox right to each player's name.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <a href="{{ route('view.ladder', ['ladderID' => $ladder->id]) }}" class="btn-blue ml-2 mr-2">
            {{ __('Back to ladder') }}
        </a>
        @foreach($links as $link)
            <a href="{{ $link['href'] }}" class="{{ $link['class'] ?? 'btn-gray' }} ml-2 mr-2">
                {{ $link['name'] }}
            </a>
        @endforeach
    </x-slot>
</x-app-layout>
