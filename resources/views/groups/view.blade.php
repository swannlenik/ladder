<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->groupName }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('groups/playerTable')

                    @if (!(bool)$group->isSingle && count($players) === 5)
                        @include('groups/suggestedOrderGroupOf5')
                    @endif

                    <table class="border-collapse border border-slate-400 w-full mt-8">
                        <thead class="bg-slate-100">
                        <tr>
                            <th class="border border-slate-300 p-2 text-right w-4/12">
                                {{ (bool)$group->isSingle ? __('Player 1') : __('Pair 1') }}
                            </th>
                            <th class="border border-slate-300 p-2 text-center w-1/12">
                                {{ __('Score P1') }}
                            </th>
                            <th class="border border-slate-300 p-2 text-center w-2/12">
                                {{ __('Update') }}
                            </th>
                            <th class="border border-slate-300 p-2 text-center w-1/12">
                                {{ __('Score P2') }}
                            </th>
                            <th class="border border-slate-300 p-2 text-left w-4/12">
                                {{ (bool)$group->isSingle ? __('Player 2') : __('Pair 2') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        <div class="w-full">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-6">
                                {{ __('Games Order') }}
                            </h2>
                        </div>

                        <div class="w-full mt-6">
                            @foreach($links as $link)
                                @if ($link['name'] === 'Create 1 Group')
                                    @continue
                                @endif

                                <a href="{{ $link['href'] }}" class="{{ $link['class'] ?? 'btn-gray' }} ml-2 mr-2">
                                    {{ $link['name'] }}
                                </a>
                            @endforeach
                        </div>

                        @foreach ($games as $game)
                            <form method="POST" action="{{ route((bool)$group->isSingle ? 'save.game' : 'save.double.game', $game->id) }}">
                                @csrf
                                <input type="hidden" name="game-id" value="{{ $game->id }}"/>
                                <input type="hidden" name="is-single" value="{{ $group->isSingle }}" />

                                @if ((bool)$group->isSingle)
                                    @include('groups/updateSingle')
                                @else
                                    @include('groups/updateDouble')
                                @endif
                            </form>
                        @endforeach

                        @if (!(bool)$group->isSingle)
                            @include('groups/createDouble')
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <x-slot name="footer">
            <a href="{{ route('view.ladder', ['ladderID' => $group->ladderId]) }}" class="btn-blue ml-2 mr-2">
                {{ __('Back to ladder') }}
            </a>
            @foreach($links as $link)
                <a href="{{ $link['href'] }}" class="{{ $link['class'] ?? 'btn-gray' }} ml-2 mr-2">
                    {{ $link['name'] }}
                </a>
            @endforeach
        </x-slot>
</x-app-layout>
