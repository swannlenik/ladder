<?php

declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Set extends Model
{
    protected $table = 'sets';
    protected $fillable = ['gameId', 'isSingle', 'setsOrder', 'score1', 'score2'];
    protected $primaryKey = ['gameID', 'isSingle', 'setsOrder'];
    public $timestamps = false;
    public $incrementing = false;

    public static function getSetsByGameId(int $gameID, int $isSingle = 1): ?Collection {
        return Set::where('gameId', '=', $gameID)->where('isSingle', '=', $isSingle)->orderBy('setsOrder', 'asc')->get();
    }

    public static function getSetsByGameIdByOrderId(int $gameID, int $orderID, int $isSingle = 1): ?Set {
        return Set::where('gameId', '=', $gameID)->where('isSingle', '=', $isSingle)->orderBy('setsOrder', 'asc')->first();
    }

    public function getWinner(): int {
        if ($this->score1 === $this->score2) {
            return 0;
        }

        return $this->score1 > $this->score2 ? 1 : 2;
    }

    public function getDifference(int $side = 1): int {
        $difference = $this->score1 - $this->score2;
        return $side === 1 ? $difference : -($difference);
    }

}
