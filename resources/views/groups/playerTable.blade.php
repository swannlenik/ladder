<table class="border-collapse border border-slate-400 w-full">
    <thead class="bg-slate-100">
    <tr>
        <th class="border border-slate-300 p-2">Player Name</th>
        <th class="border border-slate-300 p-2 text-center">Victory</th>
        <th class="border border-slate-300 p-2 text-center">Points Diff</th>
        <th class="border border-slate-300 p-2 text-center">Ranking</th>
    </tr>
    </thead>
    <tbody>
    @foreach($players as $player)
        @include('groups/playerLine')
    @endforeach
    </tbody>
</table>
