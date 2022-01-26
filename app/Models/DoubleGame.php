<?php

declare(strict_types=1);


namespace App\Models;


use App\Http\Services\GameService;
use Illuminate\Database\Eloquent\Model;

class DoubleGame extends Game
{
    protected $table = 'doubles';
    protected $fillable = ['groupId', 'opponent1', 'opponent2', 'opponent3', 'opponent4', 'score1', 'score2', 'deletable'];
    public $timestamps = false;

    public function isWinner(int $playerID): bool {
        if ($this->score1 === $this->score2) {
            return false;
        } else {
            if ($playerID === $this->opponent1 || $playerID === $this->opponent2) {
                return $this->score1 > $this->score2;
            } else {
                return $this->score1 < $this->score2;
            }
        }
    }

    public function getPointsByPlayerId(int $playerID): int {
        if ($playerID === $this->opponent1 || $playerID === $this->opponent2) {
            return $this->score1;
        } else {
            return $this->score2;
        }
    }

    public function getPointsDifference(int $playerID): int {
        if ($playerID === $this->opponent1 || $playerID === $this->opponent2) {
            return $this->score1 - $this->score2;
        } else {
            return $this->score2 - $this->score1;
        }
    }
}
