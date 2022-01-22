<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Http\Services\PlayersService;
use App\Http\Services\ResultsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GamesController extends Controller
{
    private ResultsService $resultsService;
    private PlayersService $playersService;

    public function __construct(
        ResultsService $resultsService,
        PlayersService $playersService) {
        $this->resultsService = $resultsService;
        $this->playersService = $playersService;
        $this ->links = array_merge(//$this->getCommonLinks(),
                [
                'back-to-group' => [
                    'name' => 'Back to group',
                    'href' => '',
                ],
            ]
        );
    }

    public function update(int $gameID): View {
        $game = $this->resultsService->getGameById($gameID);
        $players = $this->playersService->getPlayersByGameId($gameID);
        $this->links['back-to-group']['href'] = route('view.group', ['groupID' => $game->groupId]);

        return view('games/update', [
            'isSingle' => true,
            'game' => $game,
            'players' => $players,
            'links' => $this->links,
            'navigationLinks' => $this->getNavigationLinks(),
        ]);
    }

    public function updateDouble(int $gameID): View {
        $game = $this->resultsService->getDoubleGameById($gameID);
        $players = $this->playersService->getDoublePlayersByGameId($gameID);
        $this->links['back-to-group']['href'] = route('view.group', ['groupID' => $game->groupId]);

        return view('games/update', [
            'isSingle' => false,
            'game' => $game,
            'players' => $players,
            'links' => $this->links,
            'navigationLinks' => $this->getNavigationLinks(),
        ]);
    }

    public function save(Request $request): RedirectResponse {
        $game = $this->resultsService->saveGame($request->post());
        return redirect()->route('view.group', ['groupID' => $game->groupId]);
    }

    public function saveDouble(Request $request): RedirectResponse {
        $game = $this->resultsService->saveDoubleGame($request->post());
        return redirect()->route('view.group', ['groupID' => $game->groupId]);
    }
}
