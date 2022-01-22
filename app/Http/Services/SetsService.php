<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Set;

class SetsService
{
    public function getSetsByGameId(int $gameID, int $isSingle = 1): array {
        $sets = Set::where('gameId', '=', $gameID)
            ->where('isSingle', '=', $isSingle)
            ->orderBy('order', 'asc')
            ->get();
    }
}
