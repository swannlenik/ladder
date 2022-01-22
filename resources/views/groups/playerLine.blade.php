<tr>
    <td class="border border-slate-300 p-2 w-9/12">{{ $player->name }}</td>
    <td
        class="border border-slate-300 p-2 text-center w-1/12">{{ $statistics[$player->id]['victories'] }}
    </td>
    <td class="border border-slate-300 p-2 text-center w-1/12">{{ $statistics[$player->id]['points'] }}</td>
    <td
        class="border border-slate-300 p-2 text-center w-1/12 {{ $statistics[$player->id]['rank'] <= 3 ? $rankingClasses[$statistics[$player->id]['rank']] : '' }}">
        {{ $statistics[$player->id]['rank'] }}
    </td>
</tr>
