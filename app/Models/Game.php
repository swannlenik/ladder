<?php

declare(strict_types=1);


namespace App\Models;


use App\Http\Services\GameService;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';
    protected $fillable = ['groupId', 'opponent1', 'opponent2', 'score1', 'score2', 'deletable'];
    public $timestamps = false;

    public function getWinner(): int {
        $sets = Set::getSetsByGameId($this->id);

        $setsWonByP1 = 0;
        $emptySets = count($sets);

        foreach ($sets as $set) {
            $emptySets -= ($set->score1 !== 0 || $set->score2 !== 0) ? 1 : 0;
            $setsWonByP1 += $set->getWinner() === 1 ? 1 : 0;
        }

        if ($setsWonByP1 === 0 && $emptySets === count($sets)) {
            return 0;
        }
        return $setsWonByP1 > (count($sets) / 2) ? $this->opponent1 : $this->opponent2;
    }

    public function isWinner(int $playerID): bool {
        $winner = $this->getWinner();
        if ($winner === 0) {
            return false;
        } else {
            return $winner === $playerID;
        }
    }

    public function getPointsByPlayerId(int $playerID): int {
        if ($playerID === $this->opponent1) {
            return $this->score1;
        } else {
            return $this->score2;
        }
    }

    public function getPointsPlayed(): int {
        return $this->score1 + $this->score2;
    }

    public function getPointsDifference(int $playerID): int {
        $sets = Set::getSetsByGameId($this->id);
        $sidePlayerID = $playerID === $this->opponent1 ? 1 : 2;
        $difference = 0;

        foreach ($sets as $set) {
            $difference += $set->getDifference($sidePlayerID);
        }

        return $difference;
    }
}
