<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Game;
use App\Models\Group;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GroupService
{
    public function getGroupById(int $groupID): Group {
        return Group::find($groupID);
    }

    public function getGroupsByLadderId(int $ladderID): Collection {
        return Group::where('ladderId', '=', $ladderID)->orderBy('id')->get();
    }

    public function getGroupsByPlayerId(int $playerID): array {
        return DB::select('
            SELECT DISTINCT g.*
            FROM `groups` g
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

    public function getStatistics(int $groupID, bool $isSingle = true): array {
        $playersService = app()->make(PlayersService::class);
        $resultsService = app()->make(ResultsService::class);
        $statistics = [];
        if ($isSingle) {
            $players = $playersService->getPLayersByGroupId($groupID);
        } else {
            $players = $playersService->getDoublePLayersByGroupId($groupID);
        }

        foreach ($players as $player) {
            $statisticsUnsorted[] = [
                'victories' => $resultsService->getVictoriesByGroupIdByPlayerId($groupID, $player->id, $isSingle),
                'points' => $resultsService->getPointsByGroupIdByPlayerId($groupID, $player->id, $isSingle),
                'playerID' => $player->id
            ];
        }

        array_multisort(
            array_column($statisticsUnsorted, 'victories'), SORT_DESC,
            array_column($statisticsUnsorted, 'points'), SORT_DESC,
            $statisticsUnsorted);
        foreach($statisticsUnsorted as $id => $su) {
            $statistics[$su['playerID']] = array_merge($su, ['rank' => $id + 1]);
        }
        return $statistics;
    }

    public function getGroupsLinksByLadderId(int $ladderID): array {
        $groups = $this->getGroupsByLadderId($ladderID);
        $links = [
            [
                'name' => 'Back to Ladder Ranking',
                'href' => route('ladder.ranking', ['ladderID' => $ladderID]),
            ],
        ];
        foreach ($groups as $group) {
            $links[] = [
                'name' => $group->groupName,
                'href' => route('view.group', ['groupID' => $group->id]),
                'class' => 'btn-gray',
            ];
        }
        return $links;
    }

    public function createGroup(array $params, bool $isSingle = true): Group {
        $group = Group::firstOrCreate([
            'groupName' => $params['group-name'] ?? $params['groupName'],
            'ladderId' => $params['group-ladder-id'] ?? $params['ladderID'],
            'isSingle' => $params['isSingle'] ?? $isSingle,
        ]);
        return $group;
    }

    public function deleteGroup(int $groupID): int {
        $resultsService = app()->make(ResultsService::class);
        $group = Group::find($groupID);
        $ladderID = $group->ladderId;
        if ((bool)$group->isSingle) {
            $games = $resultsService->getGamesByGroupId($groupID);
        } else {
            $games = $resultsService->getDoubleGamesByGroupId($groupID);
        }

        $group->forceDelete();
        foreach ($games as $game) {
            $game->forceDelete();
        }

        return $ladderID;
    }

    public function sortGroups(array $players): array {
        $results = [];
        foreach ($players as $id => $group) {
            if (isset($group) && trim($group) !== '') {
                $results[$group][] = $id;
            }
        }

        return $results;
    }

    public function createMultipleGroups(array $unsortedPlayers, int $ladderID, bool $isSingle): array {
        $sortedPlayers = $this->sortGroups($unsortedPlayers);
        $groups = [];

        foreach (array_keys($sortedPlayers) as $groupName) {
            $group = $this->createGroup([
                'groupName' => $groupName,
                'ladderID' => $ladderID,
                'isSingle' => $isSingle ? 1 : 0,
            ]);
            $groups[$group->id] = $group;
        }
        return $groups;
    }
}
