<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Http\Services\AccessRightsService;
use App\Http\Services\PlayersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlayersController extends Controller
{
    private PlayersService $playersService;
    private AccessRightsService $accessRightsService;

    public function __construct(
        PlayersService $playerService,
        AccessRightsService $accessRightsService) {
        $this->playersService = $playerService;
        $this->accessRightsService = $accessRightsService;
    }

    public function list(): View {
        $links = [];
        $list = $this->playersService->getAllPLayers();
        $listAccessRights = $this->accessRightsService->hasAccessToPages(auth()->user()->getAuthIdentifier(),
            ['create.player', 'set.available.players', 'set.all.available.players']);
        if (isset($listAccessRights['set.available.players']) && $listAccessRights['set.available.players'] === 'RW') {
            $links['set.available.players'] = [
                'name' => 'Available Players',
                'href' => route('available.players'),
                'class' => 'btn-blue',
            ];
        }
        if (isset($listAccessRights['set.all.available.players']) && $listAccessRights['set.all.available.players'] === 'RW') {
            $links['set.all.available.players'] = [
                'name' => 'Set All Players Available',
                'href' => route('set.all.available.players'),
                'class' => 'btn-blue',
            ];
        }

        return view('players/list', [
            'players' => $list,
            'links' => $links,
            'navigationLinks' => $this->getNavigationLinks(),
            'accessRights' => $listAccessRights,
        ]);
    }

    public function create(Request $request): RedirectResponse {
        $player = $this->playersService->createPlayer($request->post());

        return redirect()->route('view.players');
    }

    public function delete(int $playerID): RedirectResponse {
        $this->playersService->deletePlayer($playerID);

        return redirect()->route('view.players');
    }

    public function setAvailable(Request $request): RedirectResponse {
        $playersAvailable = $request->post('available-player');
        $this->playersService->setPlayersAvailable($playersAvailable);

        return redirect()->route('view.players');
    }

    public function setAllAvailable(Request $request): RedirectResponse {
        $this->playersService->setAllPlayersAvailable();

        return redirect()->route('view.players');
    }

    public function available(): View {
        $players = $this->playersService->getAllPLayers();

        return view('players/available', [
            'players' => $players,
            'links' => [
                [
                    'name' => 'View Players',
                    'href' => route('view.players'),
                    'class' => 'btn-blue',
                ],
            ],
            'navigationLinks' => $this->getNavigationLinks(),
        ]);
    }
}
