<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistics') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" x-data={showPlayers:{{ isset($player) ? 'false' : 'true' }}}>
                    <div class="w-full p-2">
                        <a x-on:click.prevent="showPlayers=!showPlayers" class="no-underline w-full">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Players') }}
                                <i x-show="showPlayers">({{ __('Click to collapse') }})</i>
                                <i x-show="!showPlayers">({{ __('Click to expand') }})</i>
                            </h2>
                        </a>
                    </div>

                    <div class="grid grid-cols-6 gap-4">
                        @foreach ($players as $p)
                            <div class="w-full text-center" x-show="showPlayers">
                                <a href="{{ route('player.statistics', ['playerID' => $p->id]) }}">
                                    {{ $p->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if (isset($player))
                        <div class="w-full mt-6 p-2">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Statistics') }} {{ $player->name }}
                            </h2>

                            <div class="w-full p-2">
                                <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                                    {{ __('Ladders') }}
                                </h3>

                                <div class="grid grid-cols-3 gap-4">
                                    <div class="p-2">
                                        {{ __('Ladders played') }}: {{ count($statistics['ladders']) }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Singles Ladders') }}:
                                        {{ count( array_filter($statistics['ladders'], function($row) { return $row->isSingle === 1; }) ) }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Doubles Ladders') }}:
                                        {{ count( array_filter($statistics['ladders'], function($row) { return $row->isSingle === 0; }) ) }}
                                    </div>
                                </div>
                            </div>

                            <div class="w-full p-2">
                                <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                                    {{ __('Games') }}
                                </h3>

                                <div class="grid grid-cols-3 gap-4">
                                    <div class="p-2">
                                        {{ __('Games played') }}: {{ count($statistics['games']['singles']) + count($statistics['games']['doubles']) }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Singles Games') }}:
                                        {{ count($statistics['games']['singles']) }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Doubles Games') }}:
                                        {{ count($statistics['games']['doubles']) }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Games Won') }}:
                                        {{ $statistics['wins']['singles'] + $statistics['wins']['doubles'] }}
                                        @if (count($statistics['games']['singles'])+count($statistics['games']['doubles']) > 0)
                                            ({{ sprintf('%.2f', ($statistics['wins']['singles'] + $statistics['wins']['doubles'])/(count($statistics['games']['singles']) + count($statistics['games']['doubles']))*100 ) }}%)
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        {{ __('Singles Games Won') }}:
                                        {{ $statistics['wins']['singles'] }}
                                        @if (count($statistics['games']['singles']) > 0)
                                            ({{ sprintf('%.2f', $statistics['wins']['singles']/count($statistics['games']['singles'])*100 ) }}%)
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        {{ __('Doubles Games Won') }}:
                                        {{ $statistics['wins']['doubles'] }}
                                        @if (count($statistics['games']['doubles']) > 0)
                                            ({{ sprintf('%.2f', $statistics['wins']['doubles']/count($statistics['games']['doubles'])*100 ) }}%)
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="w-full p-2">
                                <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                                    {{ __('Points Total and Points Difference') }}
                                </h3>

                                <div class="grid grid-cols-3 gap-4">
                                    <div class="p-2">
                                        {{ __('Global Points Played') }}: {{ $statistics['pp']['singles'] + $statistics['pp']['doubles'] }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Singles Points Won') }}:
                                        {{ $statistics['pp']['singles'] }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Doubles Points Won') }}:
                                        {{ $statistics['pp']['doubles'] }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Global Points Won') }}:
                                        {{ $statistics['points']['singles'] + $statistics['points']['doubles'] }}
                                        @if ($statistics['pp']['singles']+$statistics['pp']['doubles'] > 0)
                                        ({{ sprintf('%.2f', ($statistics['points']['singles'] + $statistics['points']['doubles'])/($statistics['pp']['singles'] + $statistics['pp']['doubles'])*100 ) }}%)
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        {{ __('Singles Points Won') }}:
                                        {{ $statistics['points']['singles'] }}
                                        @if ($statistics['pp']['singles'] > 0)
                                        ({{ sprintf('%.2f', $statistics['points']['singles']/$statistics['pp']['singles']*100 ) }}%)
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        {{ __('Doubles Points Won') }}:
                                        {{ $statistics['points']['doubles'] }}
                                        @if ($statistics['pp']['doubles'] > 0)
                                        ({{ sprintf('%.2f', $statistics['points']['doubles']/$statistics['pp']['doubles']*100 ) }}%)
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        {{ __('Global Points Difference') }}: {{ $statistics['pa']['singles'] + $statistics['pa']['doubles'] }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Singles PD') }}:
                                        {{ $statistics['pa']['singles'] }}
                                    </div>
                                    <div class="p-2">
                                        {{ __('Doubles PD') }}:
                                        {{ $statistics['pa']['doubles'] }}
                                    </div>
                                </div>
                            </div>

                            <div class="w-full p-2">
                                <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                                    {{ __('Ladders played') }}
                                </h3>

                                <div class="grid grid-cols-3 gap-4">
                                @foreach ($statistics['ladders'] as $l)
                                    <div class="p-2">
                                        <a href="{{ route('view.ladder', ['ladderID' => $l->id]) }}">
                                            {{ $l->name }} -
                                            {{ DateTime::createFromFormat('Y-m-d H:i:s', $l->date)->format('Y-m-d') }} -
                                        </a>
                                        @foreach ($statistics['groups'] as $g)
                                            @if ($g->ladderId === $l->id)
                                                <a href="{{ route('view.group', ['groupID' => $g->id]) }}">
                                                    {{ $g->groupName }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                                </div>
                            </div>
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
