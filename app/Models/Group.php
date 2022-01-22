<?php

declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $fillable = ['ladderId', 'groupName', 'deletable', 'isSingle'];
    public $timestamps = false;

    public function getGamesByPlayerId(int $playerID): array {
        $gamesList = [];
        /** @var Game $game */
        foreach ($this->games as $game) {
            if ($game->getOpponent1() === $playerID || $game->getOpponent2() === $playerID) {
                $gamesList[] = $game;
            }
        }
        return $gamesList;
    }

    public function getVictoryByPlayerId(int $playerID): int {
        $victory = 0;
        /** @var Game $game */
        foreach ($this->games as $game) {
            if ($game->getWinner() === $playerID) {
                $victory++;
            }
        }
        return $victory;
    }

    public function getPointsByPlayerId(int $playerID): int {
        $points = 0;
        /** @var Game $game */
        foreach ($this->getGamesByPlayerId($playerID) as $game) {
            if ($game->getWinner() === $playerID) {
                $points += $game->getPointsForWinner();
            } else {
                $points -= $game->getPointsForWinner();
            }
        }
        return $points;
    }

    public function getClassRanking(int $playerID): string {
        $ranking = $this->getRanking($playerID);
        switch ($ranking) {
            case 1:
                return 'group__player-ranking--gold';
            case 2:
                return 'group__player-ranking--silver';
            case 3:
                return 'group__player-ranking--bronze';
            default:
                return '';
        }
    }
}
