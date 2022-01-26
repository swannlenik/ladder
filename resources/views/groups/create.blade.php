<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ladder') }}: {{ $ladder->name }} - {{ $ladder->getDateToString() }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="grid grid-cols-2 gap-4">
                            @error('players')
                            <div class="alert-error">{{ $message }}</div>
                            @endif
                            @error('group-name')
                            <div class="alert-error">{{ $message }}</div>
                            @endif
                            @error('group-rank')
                            <div class="alert-error">{{ $message }}</div>
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('save.group') }}">
                        @csrf
                        <input type="hidden" name="group-ladder-id" value="{{ old('group-ladder-id', $ladder->id) }}"/>

                        <div class="w-full mt-6">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Group name') }}</h2>
                        </div>
                        <div class="py-2 w-full">
                            <input type="text" name="group-name" placeholder="{{ __('Group Name') }}" value="{{ old('group-name') }}" class="w-full {{ $errors->first('group-name') != null ? 'input-error' : '' }}" />
                        </div>

                        <div class="w-full mt-6">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Group rank') }}</h2>
                        </div>
                        <div class="py-2 w-full">
                            <input type="text" name="group-rank" placeholder="{{ __('Group Ranking') }}" value="{{ old('group-rank') }}" class="w-full {{ $errors->first('group-rank') != null ? 'input-error' : '' }}" />
                        </div>

                        <div class="w-full mt-6">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ count($players) }} {{ __('Players available') }}</h2>
                        </div>
                        <div class="grid grid-cols-4 gap-4">

                        @foreach ($players as $player)
                            <div class="w-full flex flex-row h-8 py-2">
                                <div class="w-auto max-w-sm text-right pr-2">
                                    <input type="checkbox" name="players[{{ $player->id }}]" {{ isset(old('players')[$player->id]) ? 'checked="checked"' : '' }}" />
                                </div>
                                <div class="w-full text-left">
                                    {{ $player->name }}
                                </div>
                            </div>
                        @endforeach
                        </div>

                        <div class="w-full text-left py-2">
                            <input type="submit" value="{{ __('Create new group') }}" class="btn-success" />
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <x-slot name="footer">
        <a href="{{ route('view.ladder', ['ladderID' => $ladder->id]) }}" class="btn-blue">
            {{ __('Back to ladder') }}
        </a>
    </x-slot>
</x-app-layout>
