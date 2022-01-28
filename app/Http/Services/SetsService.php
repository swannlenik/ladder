<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Ladder;
use App\Models\Set;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SetsService
{
    public function getSetsByGameId(int $gameID, int $isSingle = 1): ?Collection {
        $sets = Set::where('gameId', '=', $gameID)
            ->where('isSingle', '=', $isSingle)
            ->orderBy('setsOrder', 'asc')
            ->get();
        return $sets;
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
        $sets = [];
        $listSets = Set::select('sets.*');
        if ($isSingle) {
            $listSets->rightJoin('games', 'games.id', '=', 'sets.gameId')
                ->rightJoin('groups', 'groups.id', '=', 'games.groupId');
        } else {
            $listSets->rightJoin('doubles', 'doubles.id', '=', 'sets.gameId')
                ->rightJoin('groups', 'groups.id', '=', 'doubles.groupId');
        }
            $listSets->where('groups.id', '=', $groupId)
            ->where('sets.isSingle', '=', $isSingle ? 1 : 0)
            ->orderBy('sets.gameId', 'asc')
            ->orderBy('sets.setsOrder', 'asc');

        foreach ($listSets->get() as $set) {
            $sets[$set->gameId][$set->setsOrder] = $set;
        }
        return $sets;
    }

    public function saveScore(array $params): void {
        for ($i = 1; $i <= count($params['game-score-1']); $i++) {
            DB::table('sets')->upsert([
                'score1' => $params['game-score-1'][$i],
                'score2' => $params['game-score-2'][$i],
                'gameId' => $params['game-id'],
                'isSingle' => $params['is-single'],
                'setsOrder' => $i,
            ], ['gameId', 'isSingle', 'setsOrder'], ['score1', 'score2']);
        }
    }

    public function findByPrimaryKey(int $gameID, int $isSingle = 1, int $order = 1): Set {
        return Set::where('gameId', '=', $gameID)
            ->where('isSingle', '=', $isSingle ? 1 : 0)
            ->where('setsOrder', '=', $order)
            ->first();
    }

    public function deleteSets(int $gameID, int $isSingle): void {
        DB::table('sets')
            ->where('gameId', '=', $gameID)
            ->where('isSingle', '=', $isSingle)
            ->delete();
    }
}
