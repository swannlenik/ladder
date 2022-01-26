<?php

declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DoublePlayers extends Model
{
    protected $fillable = ['groupId', 'player1', 'player2', 'player3', 'player4', 'player5'];
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['groupId', 'player1', 'player2', 'player3', 'player4', 'player5'];
}
