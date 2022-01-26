<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Ladder;
use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlayersService
{
    public function getAllPLayers(): Collection {
        $list = [];
        $players = Player::orderBy('name')->get();
        foreach ($players as $player) {
            $list[$player->id] = $player;
        }
        return collect($list);
    }

    public function getPlayersByGroupId(int $groupId): Array {
        $list = [];
        $players = DB::select(
            'SELECT DISTINCT p.*
                FROM players p
                RIGHT JOIN games g ON g.opponent1 = p.id OR g.opponent2 = p.id
                WHERE g.groupId = ?', [$groupId]);
        foreach ($players as $player) {
            $list[$player->id] = $player;
        }
        return $list;
    }

    public function getDoublePlayersByGroupId(int $groupId): Array {
        $list = [];
        $players = DB::select(
            'SELECT DISTINCT p.*
                FROM players p
                RIGHT JOIN double_players dp ON dp.player1 = p.id OR dp.player2 = p.id OR dp.player3 = p.id OR dp.player4 = p.id OR dp.player5 = p.id
                WHERE dp.groupId = ?', [$groupId]);
        foreach ($players as $player) {
            $list[$player->id] = $player;
        }
        return $list;
    }

    public function getPlayersByGameId(int $gameID): array {
        $players = DB::select(
            'SELECT DISTINCT p.*
                   FROM players p
                   RIGHT JOIN games g ON g.opponent1 = p.id OR g.opponent2 = p.id
                   WHERE g.id = ?', [$gameID]
        );
        foreach ($players as $player) {
            $players[$player->id] = $player;
        }

        return $players;
    }

    public function getDoublePlayersByGameId(int $gameID): array {
        $players = DB::select(
            'SELECT DISTINCT p.*
                   FROM players p
                   RIGHT JOIN double_players dp ON dp.player1 = p.id OR dp.player2 = p.id OR dp.player3 = p.id OR dp.player4 = p.id OR dp.player5 = p.id
                   WHERE d.id = ?', [$gameID]
        );
        foreach ($players as $player) {
            $players[$player->id] = $player;
        }

        return $players;
    }

    public function getPlayersAvailableByLadderId(Ladder $ladder): array {
        $players = [];
        if ($ladder->isSingle) {
            $playersFromDB = DB::select('
            SELECT pa.* FROM players pa WHERE id NOT IN (
            SELECT DISTINCT p.id
            FROM ladders l
            RIGHT JOIN groups g ON g.ladderId = l.id
            RIGHT JOIN games ga ON ga.groupId = g.id
            RIGHT JOIN players p ON p.id = ga.opponent1 OR p.id = ga.opponent2
            WHERE l.id = ?) AND pa.available = 1
            ORDER BY pa.name ASC', [$ladder->id]);
        } else {
            $playersFromDB = DB::select('
            SELECT pa.* FROM players pa WHERE id NOT IN (
            SELECT DISTINCT p.id
            FROM ladders l
            RIGHT JOIN groups g ON g.ladderId = l.id
            RIGHT JOIN double_players dp ON dp.groupId = g.id
            RIGHT JOIN players p ON p.id = dp.player1 OR p.id = dp.player2 OR p.id = dp.player3 OR p.id = dp.player4 OR dp.player5 = p.id
            WHERE l.id = ?) AND pa.available = 1
            ORDER BY pa.name ASC', [$ladder->id]);
        }
        foreach ($playersFromDB as $player) {
            $players[$player->id] = $player;
        }

        return $players;
    }

    public function getPlayersAvailable(): array {
        $players = [];
        $playersFromDB = Player::where('available', '=', 1)->get();
        foreach ($playersFromDB as $player) {
            $players[$player->id] = $player;
        }

        return $players;
    }

    public function getDoublePlayersAvailableByLadderId(int $ladderID): array {
        $players = [];
        $playersFromDB = DB::select('
            SELECT pa.* FROM players pa WHERE id NOT IN (
            SELECT DISTINCT p.id
            FROM ladders l
            RIGHT JOIN groups g ON g.ladderId = l.id
            RIGHT JOIN double_players dp ON dp.groupId = g.id
            RIGHT JOIN players p ON p.id = dp.player1 OR p.id = dp.player2 OR p.id = dp.player3 OR p.id = dp.player4 OR dp.player5 = p.id
            WHERE l.id = ?) AND pa.available = 1
            ORDER BY pa.name ASC', [$ladderID]);
        foreach ($playersFromDB as $player) {
            $players[$player->id] = $player;
        }

        return $players;
    }

    public function findById(int $playerID): Player {
        return Player::find($playerID);
    }

    public function createPlayer(array $params): Player {
        return Player::firstOrCreate([
            'name' => $params['player-name'],
        ]);
    }

    public function deletePlayer(int $playerID): void {
        $player = Player::find($playerID);
        $player->forceDelete();
    }

    public function setPlayerAvailable(int $playerID, bool $isAvailable): void {
        $player = Player::find($playerID);
        $player->available = $isAvailable ? 1 : 0;
        $player->save();
    }

    public function setPlayersAvailable(array $playersAvailable): void {
        $players = $this->getAllPLayers();
        foreach ($players as $player) {
            $this->setPlayerAvailable($player->id, isset($playersAvailable[$player->id]));
        }
    }

    public function setAllPlayersAvailable(): void {
        DB::update('UPDATE players SET available = 1');
    }

    public function sortPlayersByGroupName(array $players, bool $noEmptyGroupName = true): array {
        $sortedPlayers = [];
        foreach ($players as $id => $groupName) {
            if ($noEmptyGroupName && empty($groupName)) {
                continue;
            }
            if (!empty($id)) {
                $sortedPlayers[$groupName][] = $id;
            }
        }

        return $sortedPlayers;
    }

    public function getNewOrder(Collection &$players, Collection $groups, array &$statistics): array {
        $newOrder = [];
        $resetIndex = $statistics;
        sort($resetIndex);
        $keys = array_keys($statistics);
        $index = 0;

        $item = reset($statistics);
        do {
            $gid = key($statistics);
            $first = reset($item);
            $last = end($item);
            $groupPosition = array_search(key($statistics), $keys);

            foreach ($item as $playerID => $player) {
                if ($player === $first) {
                    if ($index > 0) {
                        $prevIndex = $index - 1;
                        $l = $statistics[$keys[$prevIndex]];
                        end($l);
                        $pid = key($l);
                        $newOrder[$pid] = $players[$pid];
                        $newOrder[$pid]->groupId = $gid;
                    } else {
                        $newOrder[$playerID] = $players[$playerID];
                    }
                } elseif ($player === $last) {
                    if ($index + 1 < count($groups)) {
                        $nextIndex = $index + 1;
                        $pid = key($statistics[$keys[$nextIndex]]);
                        $newOrder[$pid] = $players[$pid];
                        $newOrder[$pid]->groupId = $gid;
                    } else {
                        $newOrder[$playerID] = $players[$playerID];
                    }
                } else {
                    $newOrder[$playerID] = $players[$playerID];
                }
            }

            $index++;
        } while ($item = next($statistics));

        return $newOrder;
    }
}
