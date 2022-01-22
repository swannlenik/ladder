<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlayersService
{
    public function getAllPLayers(): Collection {
        $list = [];
        $players = Player::all();
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
                RIGHT JOIN doubles d ON d.opponent1 = p.id OR d.opponent2 = p.id OR d.opponent3 = p.id OR d.opponent4 = p.id
                WHERE d.groupId = ?', [$groupId]);
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
                   RIGHT JOIN doubles d ON d.opponent1 = p.id OR d.opponent2 = p.id OR d.opponent3 = p.id OR d.opponent4 = p.id
                   WHERE d.id = ?', [$gameID]
        );
        foreach ($players as $player) {
            $players[$player->id] = $player;
        }

        return $players;
    }

    public function getPlayersAvailableByLadderId(int $ladderID): array {
        $players = [];
        $playersFromDB = DB::select('
            SELECT pa.* FROM players pa WHERE id NOT IN (
            SELECT DISTINCT p.id
            FROM ladders l
            RIGHT JOIN groups g ON g.ladderId = l.id
            RIGHT JOIN games ga ON ga.groupId = g.id
            RIGHT JOIN players p ON p.id = ga.opponent1 OR p.id = ga.opponent2
            WHERE l.id = ?) AND pa.available = 1', [$ladderID]);
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
            RIGHT JOIN doubles d ON d.groupId = g.id
            RIGHT JOIN players p ON p.id = d.opponent1 OR p.id = d.opponent2 OR p.id = d.opponent3 OR p.id = d.opponent4
            WHERE l.id = ?) AND pa.available = 1', [$ladderID]);
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
}
