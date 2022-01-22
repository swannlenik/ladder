<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Http\Services\GroupService;
use App\Http\Services\LadderService;
use App\Http\Services\PlayersService;
use App\Http\Services\ResultsService;
use App\Models\Ladder;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    protected PlayersService $playersService;
    protected ResultsService $resultsService;
    protected LadderService $ladderService;
    protected GroupService $groupService;

    public function __construct(
        PlayersService $playersService,
        ResultsService $resultsService,
        LadderService $ladderService,
        GroupService $groupService
    )
    {
        $this->playersService = $playersService;
        $this->resultsService = $resultsService;
        $this->ladderService = $ladderService;
        $this->groupService = $groupService;
    }

    /**
     * @param int $playerID
     * @return View
     */
    public function view(int $playerID = 0): View {
        $players = $this->playersService->getAllPLayers();
        $statistics = [];

        if ($playerID > 0) {
            $player = $players[$playerID];

            $statistics = $this->resultsService->getStatisticsByPlayerId($playerID);
            $statistics['ladders'] = $this->ladderService->getLaddersPlayedByPlayerId($playerID);
            $statistics['groups'] = $this->groupService->getGroupsByPlayerId($playerID);
        } else {
            $player = null;
        }

        return view('statistics/view', [
            'players' => $players,
            'player' => $player,
            'statistics' => $statistics,
            'links' => [],
            'navigationLinks' => $this->getNavigationLinks(),
        ]);
    }
}
