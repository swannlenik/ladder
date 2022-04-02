<tr>
    <td class="border border-slate-300 p-2 text-right {{ $game->getWinner() === $game->opponent1 ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent1]->name }}
    </td>

    @if (isset($accessRights['update.game']) && $accessRights['update.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent1 ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
            <input type="text" name="game-score-1[{{ $i }}]" value="{{ $sets[$game->id][$i]->score1 }}" class="text-center" />
            @endfor
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent1 ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <div class="w-full">{{ $sets[$game->id][$i]->score1 }}"</div>
            @endfor
        </td>
    @endif

    <td class="border border-slate-300 p-2 text-center">
        @if (isset($accessRights['update.game']) && $accessRights['update.game'] === 'RW')
            <input type="submit" name="update-game" value="{{ __('Update') }}" class="btn-green" />
        @endif
        @if (isset($accessRights['delete.game']) && $accessRights['delete.game'] === 'RW')
            <a href="{{ route('delete.game', $game->id) }}" class="btn-red">
                {{ __('Delete') }}
            </a>
        @endif
    </td>

    @if (isset($accessRights['update.game']) && $accessRights['update.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent2 ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <input type="text" name="game-score-2[{{ $i }}]" value="{{ $sets[$game->id][$i]->score2 }}" class="text-center w-full" />
            @endfor
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent2 ? 'bg-green-400' : '' }}">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <div class="w-full">{{ $sets[$game->id][$i]->score2 }}"</div>
            @endfor
        </td>
    @endif

    <td class="border border-slate-300 p-2 {{ $game->getWinner() === $game->opponent2 ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent2]->name }}
    </td>
</tr>
