<?php

declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $table = 'sets';
    protected $fillable = ['gameId', 'isSingle', 'order', 'score1', 'score2'];
    protected $primaryKey = ['gameID', 'isSingle', 'order'];
    public $timestamps = false;
    public $incrementing = false;

    public function getWinner(): int {
        if ($this->score1 === $this->score2) {
            return 0;
        }

        return $this->score1 > $this->score2 ? 1 : 2;
    }

    public function getPointsDifference(int $side = 1): int {
        $difference = $this->score1 - $this->score2;
        return $side === 1 ? $difference : -($difference);
    }
}
