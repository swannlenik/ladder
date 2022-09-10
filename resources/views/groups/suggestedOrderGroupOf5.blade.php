<?php sort($players); ?>
<h1 class="suggested-order__title">{{ __('Suggested order of games') }}</h1>
<ul>
@foreach ($suggestedOrder as $game)
    <li>
        <span class="suggested-order__pair">{{ $players[$game[0]]->name }} & {{ $players[$game[1]]->name }}</span>
        <span class="suggested-order__vs">vs</span>
        <span class="suggested-order__pair">{{ $players[$game[2]]->name }} & {{ $players[$game[3]]->name }}</span>
    </li>
@endforeach
</ul>
<div class="suggested-order__explain">
    This order will ensure that everyone is playing with AND against everyone else without having 2x the same pair.
</div>