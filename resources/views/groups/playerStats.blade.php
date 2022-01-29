<tr>
    <td class="border border-slate-300 p-2 w-9/12">{{ $players[$playerID]->name }}</td>
    <td
        class="border border-slate-300 p-2 text-center w-1/12">{{ $data['victories'] }}
    </td>
    <td class="border border-slate-300 p-2 text-center w-1/12">{{ $data['points'] }}</td>
    <td
        class="border border-slate-300 p-2 text-center w-1/12 {{ $data['rank'] <= 3 ? $rankingClasses[$data['rank']] : '' }}">
        {{ $data['rank'] }}
    </td>
</tr>
