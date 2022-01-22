<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Score') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <table class="border-collapse border border-slate-400 w-full">
                        <thead class="bg-slate-100">
                        <tr>
                            @if ($isSingle)
                                <th class="border border-slate-300 p-2 text-center w-5/12">{{ $players[$game->opponent1]->name }}</th>
                                <th class="border border-slate-300 p-2 text-center w-2/12">vs</th>
                                <th class="border border-slate-300 p-2 text-center w-5/12">{{ $players[$game->opponent2]->name }}</th>
                            @else
                                <th class="border border-slate-300 p-2 text-center w-5/12">{{ $players[$game->opponent1]->name }}
                                    - {{ $players[$game->opponent2]->name }}</th>
                                <th class="border border-slate-300 p-2 text-center w-2/12">vs</th>
                                <th class="border border-slate-300 p-2 text-center w-5/12">{{ $players[$game->opponent3]->name }}
                                    - {{ $players[$game->opponent4]->name }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <form method="POST" action="{{ $isSingle ? route('save.game') : route('save.double.game') }}">
                                @csrf
                                <input type="hidden" name="game-id" value="{{ $game->id }}"/>
                                <td class="border border-slate-300 p-2 text-center">
                                    <input type="text" class="text-center" name="game-score-1" value="{{ $game->score1 }}" placeholder="Score {{ $isSingle ? 'Player' : 'Pair' }} 1"/>
                                </td>
                                <td class="border border-slate-300 p-2 text-center">
                                    <input type="submit" class="btn-green" value="{{ __('Save Score') }}"/>
                                </td>
                                <td class="border border-slate-300 p-2 text-center">
                                    <input type="text" class="text-center" name="game-score-2" value="{{ $game->score2 }}" placeholder="Score {{ $isSingle ? 'Player' : 'Pair' }} 2"/>
                                </td>
                            </form>
                        </tr>

                        </tbody>
                    </table>
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
