@php
    $count = 1;
    $prevGroupID = 0;
@endphp

<div class="flex flex-wrap">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        {{ __('Players Ranking') }}
    </h2>
    <div class="w-full grid grid-cols-1">
        @foreach($statistics as $groupID => $statisticsGroup)
            @foreach($statisticsGroup as $statisticsPlayer)
                <div class="w-full flex flex-wrap {{ $count === 1 || ($prevGroupID > 0 && $prevGroupID !== $groupID) ? 'group__player-ranking--gold' : '' }}">
                    <div class="w-2/12 text-right pr-2">
                        {{ $count++ }}
                    </div>
                    <div class="w-10/12 pl-2">
                        {{ $players[$statisticsPlayer['playerID']]->name }}
                    </div>
                </div>
                @php
                    $prevGroupID = $groupID;
                @endphp
            @endforeach
        @endforeach
    </div>


</div>
