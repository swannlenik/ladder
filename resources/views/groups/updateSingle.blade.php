<tr>
    <td class="border border-slate-300 p-2 text-right {{ $game->getWinner() === $game->opponent1 ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent1]->name }}
    </td>

    @if (isset($accessRights['update.game']) && $accessRights['update.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent1 ? 'bg-green-400' : '' }}">
            <input type="text" name="game-score-1" value="{{ $game->score1 }}" class="text-center" />
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent1 ? 'bg-green-400' : '' }}">
            {{ $game->score1 }}
        </td>
    @endif

    <td class="border border-slate-300 p-2 text-center">
        @if (isset($accessRights['update.game']) && $accessRights['update.game'] === 'RW')
            <input type="submit" name="update-game" value="{{ __('Update Score') }}" class="btn-green" />
        @endif
    </td>

    @if (isset($accessRights['update.game']) && $accessRights['update.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent2 ? 'bg-green-400' : '' }}">
            <input type="text" name="game-score-2" value="{{ $game->score2 }}" class="text-center" />
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->getWinner() === $game->opponent2 ? 'bg-green-400' : '' }}">
            {{ $game->score2 }}
        </td>
    @endif

    <td class="border border-slate-300 p-2 {{ $game->getWinner() === $game->opponent2 ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent2]->name }}
    </td>
</tr>
