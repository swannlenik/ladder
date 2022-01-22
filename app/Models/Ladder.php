<?php

declare(strict_types=1);


namespace App\Models;


use DateTime;
use Illuminate\Database\Eloquent\Model;

class Ladder extends Model
{
    protected $table = 'ladders';
    protected $fillable = ['name', 'date', 'isSingle', 'deletable'];
    public $timestamps = false;

    public function getDateToString(?string $format = 'Y-m-d'): string {
        $date = new DateTime($this->date);
        return $date->format($format);
    }
}
