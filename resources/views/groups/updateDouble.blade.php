<tr>
    <td
        class="border border-slate-300 p-2 text-right {{ $game->isWinner($game->opponent1) || $game->isWinner($game->opponent2) ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent1]->name }}
        - {{ $players[$game->opponent2]->name }}
    </td>

    @if (isset($accessRights['update.double.game']) && $accessRights['update.double.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent1) || $game->isWinner($game->opponent2) ? 'bg-green-400' : '' }}">
            <input type="text" name="game-score-1" value="{{ $game->score1 }}" class="text-center" />
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent1) || $game->isWinner($game->opponent2) ? 'bg-green-400' : '' }}">
            {{ $game->score1 }}
        </td>
    @endif

    <td class="border border-slate-300 p-2 text-center">
        @if (isset($accessRights['update.double.game']) && $accessRights['update.double.game'] === 'RW')
            <input type="submit" name="game-update" value="Update Score" class="btn-green text-center" />
        @endif
    </td>

    @if (isset($accessRights['update.double.game']) && $accessRights['update.double.game'] === 'RW')
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent3) || $game->isWinner($game->opponent4) ? 'bg-green-400' : '' }}">
            <input type="text" name="game-score-2" value="{{ $game->score2 }}" class="text-center" />
        </td>
    @else
        <td class="border border-slate-300 p-2 text-center {{ $game->isWinner($game->opponent3) || $game->isWinner($game->opponent4) ? 'bg-green-400' : '' }}">
            {{ $game->score2 }}
        </td>
    @endif

    <td
        class="border border-slate-300 p-2 {{ $game->isWinner($game->opponent3) || $game->isWinner($game->opponent4) ? 'bg-green-400' : '' }}">
        {{ $players[$game->opponent3]->name }}
        - {{ $players[$game->opponent4]->name }}
    </td>
</tr>
