<form method="POST" action="{{ route('save.double.game', ['gameID' => 0]) }}">
    @csrf
    <input type="hidden" name="group-id" value="{{ $group->id }}">

    <tr>
        <td class="border border-slate-300 p-2 text-right">
            <select name="opponent1">
                @foreach ($players as $player)
                    <option value="{{ $player->id }}">{{ $player->name }}</option>
                @endforeach
            </select>
            -
            <select name="opponent2">
                @foreach ($players as $player)
                    <option value="{{ $player->id }}">{{ $player->name }}</option>
                @endforeach
            </select>
        </td>

        <td class="border border-slate-300 p-2 text-center">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <input type="text" name="game-score-1[{{ $i }}]" value="0" class="text-center" />
            @endfor
        </td>

        <td class="border border-slate-300 p-2 text-center">
            <input type="submit" name="game-update" value="{{ __('Update Score') }}" class="btn-green text-center"/>
        </td>

        <td class="border border-slate-300 p-2 text-center">
            @for ($i = 1; $i <= $ladder->sets; $i++)
                <input type="text" name="game-score-2[{{ $i }}]" value="0" class="text-center" />
            @endfor
        </td>

        <td class="border border-slate-300 p-2 text-left">
            <select name="opponent3">
                @foreach ($players as $player)
                    <option value="{{ $player->id }}">{{ $player->name }}</option>
                @endforeach
            </select>
            -
            <select name="opponent4">
                @foreach ($players as $player)
                    <option value="{{ $player->id }}">{{ $player->name }}</option>
                @endforeach
            </select>
        </td>
    </tr>
</form>
