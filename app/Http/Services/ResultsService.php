<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\DoubleGame;
use App\Models\DoublePlayers;
use App\Models\Game;
use App\Models\Group;
use Illuminate\Support\Collection;

class ResultsService
{
    private const GROUP_3_GAMES_SINGLES_INDEX = [
        [1, 2],
        [0, 2],
        [0, 1],
    ];
    private const GROUP_4_GAMES_SINGLES_INDEX = [
        [0, 2],
        [1, 3],
        [0, 3],
        [1, 2],
        [0, 1],
        [2, 3],
    ];
    private const GROUP_5_GAMES_SINGLES_INDEX = [
        [1, 3],
        [2, 4],
        [0, 2],
        [3, 4],
        [0, 3],
        [1, 4],
        [0, 4],
        [1, 2],
        [0, 1],
        [2, 3],
    ];
    private const GROUP_4_GAMES_DOUBLES_INDEX = [
        [0, 1, 2, 3],
        [0, 2, 1, 3],
        [0, 3, 1, 2],
    ];
    private const GROUP_5_GAMES_DOUBLES_INDEX = [
        [0, 1, 2, 3],
        [0, 4, 1, 2],
        [0, 3, 1, 4],
        [0, 2, 3, 4],
        [1, 3, 2, 4],
    ];

    public function getResults(?int $ladderID, ?int $groupID): array
    {
        $results = explode("\n", trim(file_get_contents(resource_path('data/resultsByDay.txt'))));
        $index = 0;

        if (isset($ladderID) && isset($groupID)) {
            foreach ($results as $result) {
                if ($result !== '') {
                    $game = explode(';', $result);
                    if ((int)$game[1] !== $ladderID || (int)$game[2] !== $groupID) {
                        array_splice($results, $index, 1);
                    } else {
                        $index++;
                    }
                }
            }
        }
        return $results;
    }

    public function getGameById(int $gameID): ?Game
    {
        return Game::find($gameID);
    }

    public function getDoubleGameById(int $gameID): ?Game
    {
        return DoubleGame::find($gameID);
    }

    public function getGamesByGroupId(int $groupID): Collection
    {
        return Game::where('groupId', '=', $groupID)->orderBy('id', 'asc')->get();
    }

    public function getDoubleGamesByGroupId(int $groupID): Collection
    {
        return DoubleGame::where('groupId', '=', $groupID)->get();
    }

    public function getSingleGamesByGroupIdByPlayerId(int $groupID, int $playerID): Collection
    {
        $games = Game::where('groupId', '=', $groupID)
            ->where(function ($query) use ($playerID) {
                $query->where('opponent1', '=', $playerID)->orWhere('opponent2', '=', $playerID);
            })
            ->get();
        return $games;
    }

    public function getDoubleGamesByGroupIdByPlayerId(int $groupID, int $playerID): Collection
    {
        $games = DoubleGame::where('groupId', '=', $groupID)
            ->where(function ($query) use ($playerID) {
                $query->where('opponent1', '=', $playerID)
                    ->orWhere('opponent2', '=', $playerID)
                    ->orWhere('opponent3', '=', $playerID)
                    ->orWhere('opponent4', '=', $playerID);
            })->get();
        return $games;
    }

    public function getVictoriesByGroupIdByPlayerId(int $groupID, int $playerID, bool $isSingle = true): int
    {
        $victory = 0;
        if ($isSingle) {
            $gamesByPlayerId = $this->getSingleGamesByGroupIdByPlayerId($groupID, $playerID);
        } else {
            $gamesByPlayerId = $this->getDoubleGamesByGroupIdByPlayerId($groupID, $playerID);
        }

        /** @var Game $game */
        foreach ($gamesByPlayerId as $game) {
            if ($isSingle) {
                $victory += $game->getWinner() === $playerID ? 1 : 0;
            } else {
                $victory += $game->isWinner($playerID) ? 1 : 0;
            }
        }

        return $victory;
    }

    public function getPointsByGroupIdByPlayerId(int $groupID, int $playerID, bool $isSingle = true): int
    {
        $points = 0;
        if ($isSingle) {
            $gamesByPlayerId = $this->getSingleGamesByGroupIdByPlayerId($groupID, $playerID);
        } else {
            $gamesByPlayerId = $this->getDoubleGamesByGroupIdByPlayerId($groupID, $playerID);
        }
        /** @var Game $game */
        foreach ($gamesByPlayerId as $game) {
            $points += $game->getPointsDifference($playerID);
        }

        return $points;
    }

    public function saveGame(array $params): Game
    {
        $game = $this->getGameById((int)$params['game-id']);
        $game->score1 = (int)$params['game-score-1'];
        $game->score2 = (int)$params['game-score-2'];
        $game->save();
        return $game;
    }

    public function saveDoubleGame(array $params): DoubleGame
    {
        if (empty($params['game-id'])) {
            $game = new DoubleGame();
            $game->opponent1 = $params['opponent1'];
            $game->opponent2 = $params['opponent2'];
            $game->opponent3 = $params['opponent3'];
            $game->opponent4 = $params['opponent4'];
            $game->groupId = $params['group-id'];
        } else {
            $game = $this->getDoubleGameById((int)$params['game-id']);
        }
        $game->score1 = (int)$params['game-score-1'];
        $game->score2 = (int)$params['game-score-2'];
        $game->save();
        return $game;
    }

    public function createSingleGame(array $params, int $groupID): Game
    {
        $params = [
            'groupId' => $groupID,
            'opponent1' => $params['opponent1'],
            'opponent2' => $params['opponent2'],
            'score1' => $params['score1'] ?? 0,
            'score2' => $params['score2'] ?? 0
        ];
        return Game::firstOrCreate($params);
    }

    public function createDoubleGame(Group $group, array $params): DoublePlayers
    {
        $groupParams = [
            'groupId' => $group->id,
            'player1' => $params[0],
            'player2' => $params[1],
            'player3' => $params[2],
            'player4' => $params[3],
            'player5' => $params[4] ?? 0,
        ];
        return DoublePlayers::firstOrCreate($groupParams);
    }

    public function createGames(Group $group, array $players, bool $fixedOrder = true): array
    {
        $games = [];
        if ($fixedOrder) {
            $p = array_keys($players);
            $order = $this->getGamesOrder(count($players));
            foreach ($order as $game) {
                $params = [
                    'opponent1' => $p[$game[0]],
                    'opponent2' => $p[$game[1]],
                ];
                $games[] = $this->createSingleGame($params, $group->id);
            }
        } else {
            foreach ($players as $p1 => $val1) {
                foreach ($players as $p2 => $val2) {
                    if ($p2 <= $p1) {
                        continue;
                    }
                    $params = [
                        'opponent1' => $p1,
                        'opponent2' => $p2,
                    ];
                    $games[] = $this->createSingleGame($params, $group->id);
                }
            }
        }

        return $games;
    }

    public function createMultipleGames(array $unsortedPlayers, array $groups, bool $isSingle = true): array
    {
        $games = [];

        foreach ($groups as $group) {
            $players = [];
            foreach ($unsortedPlayers as $playerID => $groupName) {
                if ($groupName !== (string)$group->groupName) {
                    continue;
                }
                $players[$playerID] = $playerID;
            }

            if ($isSingle) {
                $game = $this->createGames($group, $players);
            } else {
                sort($players);
                $game = $this->createDoubleGame($group, $players);
            }
            $games[] = $game;
        }

        return $games;
    }

    public function getGamesByPlayerId(int $playerID): array {
        $singles = Game::select()
            ->where('opponent1', '=', $playerID)
            ->orWhere('opponent2', '=', $playerID)
            ->get();
        $doubles = DoubleGame::select()
            ->where('opponent1', '=', $playerID)
            ->orWhere('opponent2', '=', $playerID)
            ->orWhere('opponent3', '=', $playerID)
            ->orWhere('opponent4', '=', $playerID)
            ->get();
        return [
            'singles' => $singles,
            'doubles' => $doubles
        ];
    }

    public function getStatisticsByPlayerId(int $playerID): array {
        $gamesWon = [
            'singles' => 0,
            'doubles' => 0,
        ];
        $pointAverage = [
            'singles' => 0,
            'doubles' => 0,
        ];
        $pointsWon = [
            'singles' => 0,
            'doubles' => 0,
        ];
        $pointsPlayed = [
            'singles' => 0,
            'doubles' => 0,
        ];

        $gamesPlayed = $this->getGamesByPlayerId($playerID);
        foreach ($gamesPlayed as $type => $subListGames) {
            foreach ($subListGames as $s) {
                $gamesWon[$type] += $s->isWinner($playerID) ? 1 : 0;
                $pointsPlayed[$type] += $s->getPointsPlayed($playerID);
                $pointsWon[$type] += $s->getPointsByPlayerId($playerID);
                $pointAverage[$type] += $s->getPointsDifference($playerID);
            }
        }

        return [
            'games' => $gamesPlayed,
            'wins' => $gamesWon,
            'pp' => $pointsPlayed,
            'points' => $pointsWon,
            'pa' => $pointAverage,
        ];
    }

    private function getGamesOrder(int $playersCount): array {
        switch ($playersCount) {
            case 3:
                return self::GROUP_3_GAMES_SINGLES_INDEX;
            case 4:
                return self::GROUP_4_GAMES_SINGLES_INDEX;
            case 5:
                return self::GROUP_5_GAMES_SINGLES_INDEX;
            default:
                return [];
        }
    }
}
