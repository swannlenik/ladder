<tr>
    <td
        class="border border-slate-300 p-2 text-right {{ $game->isWinner($game->opponent1) || $game->isWinner($game->opponent2) ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent1]->name }}
        - {{ $players[$game->opponent2]->name }}
    </td>

    @if (isset($accessRights['update.double.game']) && $accessRights['update.double.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent1) || $game->isWinner($game->opponent2) ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <input type="text" name="game-score-1[{{ $i }}]" value="{{ $sets[$game->id][$i]->score1 }}" class="text-center" />
            @endfor
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent1) || $game->isWinner($game->opponent2) ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <div class="w-full">{{ $sets[$game->id][$i]->score1 }}"</div>
            @endfor
        </td>
    @endif


    <td class="border border-slate-300 p-2 text-center">
        @if (isset($accessRights['update.double.game']) && $accessRights['update.double.game'] === 'RW')
            <input type="submit" name="game-update" value="{{ __('Update') }}" class="btn-green text-center" />
        @endif
        @if (isset($accessRights['delete.double.game']) && $accessRights['delete.double.game'] === 'RW')
            <a href="{{ route('delete.double.game', $game->id) }}" class="btn-red">
                {{ __('Delete') }}
            </a>
        @endif
    </td>

    @if (isset($accessRights['update.double.game']) && $accessRights['update.double.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent3) || $game->isWinner($game->opponent4) ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <input type="text" name="game-score-2[{{ $i }}]" value="{{ $sets[$game->id][$i]->score2 }}" class="text-center" />
            @endfor
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent3) || $game->isWinner($game->opponent4) ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <div class="w-full">{{ $sets[$game->id][$i]->score2 }}"</div>
            @endfor
        </td>
    @endif

    <td
        class="border border-slate-300 p-2 {{ $game->isWinner($game->opponent3) || $game->isWinner($game->opponent4) ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent3]->name }}
        - {{ $players[$game->opponent4]->name }}
    </td>
</tr>
