<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Ladder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LadderService
{

    public function getAll(): Collection {
        return Ladder::all();
    }

    public function findById(int $ladderID): Ladder {
        return Ladder::find($ladderID);
    }

    public function createLadder(array $params): Ladder {
        $name = $params['ladder-name'];
        $date = new \DateTime($params['ladder-date']);
        $isSingle = isset($params['ladder-is-single']);

        $ladder = Ladder::firstOrCreate([
            'name' => $name,
            'date' => $date->format('Y-m-d H:i:s'),
            'isSingle' => $isSingle,
            'deletable' => 0,
        ]);
        return $ladder;
    }

    public function getLaddersPlayedByPlayerId(int $playerID): array {
        return DB::select('
            SELECT DISTINCT l.*
            FROM `ladders` l
            LEFT JOIN `groups` g ON g.ladderId = l.id
            LEFT JOIN `games` ga ON ga.groupId = g.id
            LEFT JOIN `doubles` d ON d.groupId = g.id
            WHERE ga.opponent1 = ?
                OR ga.opponent2 = ?
                OR d.opponent1 = ?
                OR d.opponent2 = ?
                OR d.opponent3 = ?
                OR d.opponent4 = ?
        ', [$playerID, $playerID, $playerID, $playerID, $playerID, $playerID]);
    }
}
