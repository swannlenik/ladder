<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Ladder;
use App\Models\Set;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SetsService
{
    public function getSetsByGameId(int $gameID, int $isSingle = 1): array {
        $sets = Set::where('gameId', '=', $gameID)
            ->where('isSingle', '=', $isSingle)
            ->orderBy('setsOrder', 'asc')
            ->get();
    }

    public function createSets(array $games, Ladder $ladder): array {
        $sets = [];
        foreach ($games as $game) {
            for ($i = 1; $i <= $ladder->sets; $i++) {
                $set = new Set();
                $set->gameId = $game->id;
                $set->isSingle = $ladder->isSingle;
                $set->setsOrder = $i;
                $set->score1 = 0;
                $set->score2 = 0;
                $set->save();
                $sets[] = $set;
            }
        }

        return $sets;
    }

    public function getSetsByGroupId(int $groupId, bool $isSingle = true): ?array {
        $listSets = Set::select('sets.*')
            ->rightJoin('games', 'games.id', '=', 'sets.gameId')
            ->rightJoin('groups', 'groups.id', '=', 'games.groupId')
            ->where('groups.id', '=', $groupId)
            ->where('sets.isSingle', '=', $isSingle ? 1 : 0)
            ->orderBy('sets.gameId', 'asc')
            ->orderBy('sets.setsOrder', 'asc')
            ->get();

        foreach ($listSets as $set) {
            $sets[$set->gameId][$set->setsOrder] = $set;
        }
        return $sets;
    }

    public function saveScore(array $params): void {
        for ($i = 1; $i <= count($params['game-score-1']); $i++) {
            DB::update('UPDATE sets SET score1 = ?, score2 = ? WHERE gameId = ? AND isSingle = ? AND setsOrder = ?',[
                $params['game-score-1'][$i],
                $params['game-score-2'][$i],
                $params['game-id'],
                $params['is-single'],
                $i
            ]);
        }
    }

    public function findByPrimaryKey(int $gameID, int $isSingle = 1, int $order = 1): Set {
        return Set::where('gameId', '=', $gameID)
            ->where('isSingle', '=', $isSingle ? 1 : 0)
            ->where('setsOrder', '=', $order)
            ->first();
    }
}
