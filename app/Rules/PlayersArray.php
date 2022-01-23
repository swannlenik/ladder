<?php

declare(strict_types=1);


namespace App\Rules;


use App\Http\Services\PlayersService;
use App\Models\Ladder;
use Illuminate\Contracts\Validation\Rule;

class PlayersArray implements Rule
{
    protected int $min;
    protected int $max = 5;

    protected array $errors = [];

    public function __construct(Ladder $ladder)
    {
        $this->min = (bool)$ladder->isSingle ? 3 : 4;
    }

    public function passes($attribute, $value) {
        $playersService = app()->make(PlayersService::class);
        $sortedArray = $playersService->sortPlayersByGroupName($value);
        foreach ($sortedArray as $groupName => $players) {
            if ($groupName === '') {
                continue;
            }
            if (count($players) < $this->min || count($players) > $this->max) {
                $this->errors[] = [
                    'name' => $groupName,
                    'underMin' => count($players) < $this->min,
                    'count' => count($players),
                ];
            }
        }

        return count($this->errors) === 0;
    }

    public function message() {
        $message = [];
        foreach ($this->errors as $error) {
            $message[] = $error['name'] . ' has too ' . ($error['underMin'] ? 'few' : 'much') . ' players: ' . $error['count'] . ' instead of ' . ($error['underMin'] ?
                    'at least ' . $this->min : 'at most ' . $this->max);
        }
        return implode(' - ', $message);
    }
}
