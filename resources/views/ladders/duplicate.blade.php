<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Duplicate Ladder') }} <i>"{{ $ladder->name }}"</i>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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

                    <form method="POST" action="{{ route('save.duplicate.ladder') }}" class="flex flex-wrap mt-6">
                        @csrf
                        <input type="hidden" name="duplicate-ladder-id" value="{{ $ladder->id }}">

                        <div class="w-2/12 text-right pr-4 mt-8">{{ __('Ladder Name') }}</div>
                        <div class="w-10/12 pl-2 mt-6">
                            <input type="text" name="ladder-name" value="{{ old('ladder-name', $ladder->name) }}" placeholder="{{ __('Name of new Ladder') }}" class="w-full" />
                        </div>

                        <div class="w-2/12 text-right pr-4 mt-8">{{ __('Ladder Date') }}</div>
                        <div class="w-10/12 pl-2 mt-6">
                            <input type="text" name="ladder-date" value="{{ old('ladder-date', DateTime::createFromFormat('Y-m-d H:i:s', $ladder->date)->format('Y-m-d')) }}" placeholder="{{ __('Date of new Ladder') }}" class="w-full" />
                        </div>

                        <h2 class="font-semibold text-xl text-gray-800 leading-tight w-full mt-6">
                            {{ __('Current players') }} ({{ count($players) }})
                        </h2>

                        <div class="grid grid-cols-3 gap-4 w-full">
                        @foreach ($players as $player)
                            <div class="flex flex-wrap">
                            <div class="w-2/12 text-right pr-4 mt-2">{{ $player->name }}</div>
                            <div class="w-10/12 pl-2">
                                <select name="players-list[{{ $player->id }}]">
                                    <option value="" {{ empty(old('players-list')[$player->id]) ? 'selected="selected"' : '' }}>{{ __('Not playing') }}</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->groupName }}"
                                            {{ (empty(old('players-list')) && $player->groupId === $group->id) || (!empty(old('players-list')) && old('players-list')[$player->id] === $group->groupName) ? 'selected="selected"' : '' }}>
                                            {{ $group->groupName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        @endforeach
                        </div>

                        <h2 class="font-semibold text-xl text-gray-800 leading-tight w-full mt-6">
                            {{ __('Add New Player(s)') }} ({{ count($available) }})
                        </h2>

                        <div class="grid grid-cols-3 gap-4 w-full">
                            @foreach ($available as $player)
                                <div class="flex flex-wrap">
                                    <div class="w-2/12 text-right pr-4 mt-2">{{ $player->name }}</div>
                                    <div class="w-10/12 pl-2">
                                        <select name="players-list[{{ $player->id }}]">
                                            <option value="" {{ empty(old('players-list')['groupId']) ? 'selected="selected"' : '' }}>{{ __('Not playing') }}</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->groupName }}" {{ !empty(old('players-list')[$player->id]) && old('players-list')[$player->id] === $group->groupName ? 'selected="selected"' : '' }}>
                                                    {{ $group->groupName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <div class="w-full text-center py-2">
                            <input type="submit" value="{{ __('Create Duplicate Ladder') }}" class="btn-green" />
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
