<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Ladder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LadderService
{
    protected PlayersService $playersService;
    protected GroupService $groupService;
    protected ResultsService $resultsService;

    public function __construct(
        PlayersService $playersService,
        GroupService $groupService,
        ResultsService $resultsService)
    {
        $this->playersService = $playersService;
        $this->groupService = $groupService;
        $this->resultsService = $resultsService;
    }

    public function getAll(): Collection {
        return Ladder::all();
    }

    public function findById(int $ladderID): Ladder {
        return Ladder::find($ladderID);
    }

    public function createLadder(array $params): Ladder {
        $name = $params['ladder-name'];
        $date = new \DateTime($params['ladder-date']);
        $isSingle = $params['ladder-is-single'] ?? false;
        $sets = $params['ladder-sets'] ?? 1;

        $ladder = Ladder::firstOrCreate([
            'name' => $name,
            'date' => $date->format('Y-m-d H:i:s'),
            'isSingle' => $isSingle,
            'deletable' => 0,
            'sets' => $sets,
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

    public function getPlayersByLadderId(Ladder $ladder): Collection {
        $players = [];
        $query = DB::table('ladders')
            ->select(['players.*', 'groups.id as groupId'])
            ->distinct()
            ->where('ladderId', '=', $ladder->id)
            ->leftJoin('groups', 'groups.ladderId', '=', 'ladders.id');

        if ($ladder->isSingle) {
            $result = $query->leftJoin('games', 'games.groupId', '=', 'groups.id')
                ->leftJoin('players', function($join) {
                    $join->on('games.opponent1', '=', 'players.id')->orOn('games.opponent2', '=', 'players.id');
                });
        } else {
            $result = $query->leftJoin('doubles', 'doubles.groupId', '=', 'groups.id')
                ->leftJoin('players', function($join) {
                    $join->on('doubles.opponent1', '=', 'players.id')
                        ->orOn('doubles.opponent2', '=', 'players.id')
                        ->orOn('doubles.opponent3', '=', 'players.id')
                        ->orOn('doubles.opponent4', '=', 'players.id');
                });
        }

        $result = $result->orderBy('players.name')->get();
        foreach ($result as $line) {
            $players[$line->id] = $line;
        }
        return collect($players);
    }

    public function duplicateLadder(array $params): array {
        $ladder = $this->createLadder($params);
        $groups = $this->groupService->createMultipleGroups($params['players-list'], $ladder->id, $params['ladder-is-single']);
        $games = $this->resultsService->createMultipleGames($params['players-list'], $groups, $params['ladder-is-single']);

        $result = [
            'ladder' => $ladder,
            'groups' => $groups,
            'games' => $games,
        ];

        return $result;
    }
}
