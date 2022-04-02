<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Http\Services\AccessRightsService;
use App\Http\Services\GroupService;
use App\Http\Services\LadderService;
use App\Http\Services\PlayersService;
use App\Rules\PlayersArray;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LaddersController extends Controller
{
    private AccessRightsService $accessRightsService;
    private GroupService $groupService;
    private LadderService $ladderService;
    private PlayersService $playersService;

    public function __construct(
        AccessRightsService $accessRightsService,
        GroupService        $groupService,
        LadderService       $ladderService,
        PlayersService      $playersService
    )
    {
        $this->accessRightsService = $accessRightsService;
        $this->groupService = $groupService;
        $this->ladderService = $ladderService;
        $this->playersService = $playersService;

        $this->links = array_merge(
            [
                'bunch' => [
                    'name' => 'Create multiple groups',
                    'href' => '',
                    'class' => 'btn-green',
                ],
            ]);
    }

    public function list(): View
    {
        $ladders = $this->ladderService->getAll();
        $this->navigationLinks = $this->getNavigationLinks(auth()->user()->getAuthIdentifier());
        $listAccessRights = $this->accessRightsService->hasAccessToPages(auth()->user()->getAuthIdentifier(), ['delete.ladder', 'create.ladder', 'duplicate.ladder']);
        unset($this->links['bunch']);

        return view('ladders/list', [
            'ladders' => $ladders,
            'links' => $this->links,
            'navigationLinks' => $this->navigationLinks,
            'accessRights' => $listAccessRights,
        ]);
    }

    public function view(int $ladderID): View
    {
        $ladder = $this->ladderService->findById($ladderID);
        $groups = $this->groupService->getGroupsByLadderId($ladderID);
        $this->navigationLinks = $this->getNavigationLinks(auth()->user()->getAuthIdentifier());
        $listAccessRights = $this->accessRightsService->hasAccessToPages(auth()->user()->getAuthIdentifier(), ['delete.group', 'create.group', 'create.groups']);
        if (isset($listAccessRights['create.groups']) && $listAccessRights['create.groups'] === 'RW') {
            $this->links['bunch']['href'] = route('create.groups', ['ladderID' => $ladderID]);
        } else {
            unset($this->links['bunch']);
        }
        $this->links['ranking'] = [
            'name' => 'Ladder Rankings',
            'href' => route('ladder.ranking', ['ladderID' => $ladderID]),
        ];
        $this->links['duplicate'] = [
            'name' => 'Duplicate Ladder',
            'href' => route('duplicate.ladder', ['ladderID' => $ladderID]),
            'class' => 'btn-green',
        ];


        return view('ladders/view', [
            'ladder' => $ladder,
            'groups' => $groups,
            'links' => $this->links,
            'navigationLinks' => $this->navigationLinks,
            'accessRights' => $listAccessRights,
        ]);
    }

    public function ranking(int $ladderID): View
    {
        $this->navigationLinks = $this->getNavigationLinks(auth()->user()->getAuthIdentifier());
        $listAccessRights = $this->accessRightsService->hasAccessToPages(auth()->user()->getAuthIdentifier(), ['delete.group', 'create.group', 'create.groups']);
        $this->rankingClasses = [
            1 => 'group__player-ranking--gold',
            2 => 'group__player-ranking--silver',
            3 => 'group__player-ranking--bronze',
        ];

        $statisticsByGroup = [];
        $playersByGroup = [];
        $ladder = $this->ladderService->findById($ladderID);
        $groups = $this->groupService->getGroupsByLadderId($ladderID, 'rank');
        foreach ($groups as $group) {
            if ($group->isSingle === 1) {
                $playersByGroup[$group->id] = $this->playersService->getPlayersByGroupId($group->id);
            } else {
                $playersByGroup[$group->id] = $this->playersService->getDoublePlayersByGroupId($group->id);
            }
            $statisticsByGroup[$group->id] = $this->groupService->getStatistics($group->id, (bool)$ladder->isSingle);
        }
        $nextLadderEnabled = $this->groupService->canCreateNextLadder($statisticsByGroup);

        $this->links = [
            [
                'name' => 'Back to Ladder',
                'href' => route('view.ladder', ['ladderID' => $ladderID]),
            ],
            [
                'name' => 'Duplicate Ladder',
                'href' => route('duplicate.ladder', ['ladderID' => $ladderID]),
            ],
        ];
        if ($nextLadderEnabled) {
            $this->links[] = [
                'name' => 'Next Ladder',
                'href' => route('next.ladder', ['ladderID' => $ladderID]),
                'class' => 'btn-green',
            ];
        }

        return view('ladders/rankings', [
            'ladder' => $ladder,
            'groups' => $groups,
            'statisticsByGroup' => $statisticsByGroup,
            'playersByGroup' => $playersByGroup,
            'links' => $this->links,
            'navigationLinks' => $this->navigationLinks,
            'accessRights' => $listAccessRights,
            'rankingClasses' => $this->rankingClasses,
            'nextLadderEnabled' => $nextLadderEnabled,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $ladder = $this->ladderService->createLadder($request->post());
        if (isset($ladder)) {
            return redirect()->route('view.ladder', ['ladderID' => $ladder->id]);
        } else {
            abort(500);
        }
    }

    public function delete(int $ladderID): RedirectResponse
    {
        $ladder = $this->ladderService->findById($ladderID);
        $groups = $this->groupService->getGroupsByLadderId($ladderID);
        foreach ($groups as $group) {
            $this->groupService->deleteGroup($group->id);
        }
        $ladder->forceDelete();

        return redirect()->route('view.all.ladders');
    }

    public function duplicate(int $ladderID): View {
        $statistics = [];
        $ladder = $this->ladderService->findById($ladderID);
        $groups = $this->groupService->getGroupsByLadderId($ladderID, 'rank');
        $players = $this->ladderService->getPlayersByLadderId($ladder);
        $availablePlayers = $this->playersService->getPlayersAvailableByLadderId($ladder);
        foreach ($groups as $group) {
            $statistics[$group->id] = $this->groupService->getStatistics($group->id, (bool)$ladder->isSingle);
        }

        return view('ladders/duplicate', [
            'ladder' => $ladder,
            'groups' => $groups,
            'players' => $players,
            'available' => $availablePlayers,
            'statistics' => $statistics,
            'links' => [],
            'navigationLinks' => $this->getNavigationLinks(),
        ]);
    }

    public function next(int $ladderID): View {
        $statistics = [];
        $ladder = $this->ladderService->findById($ladderID);
        $groups = $this->groupService->getGroupsByLadderId($ladderID, 'rank');
        $players = $this->ladderService->getPlayersByLadderId($ladder);
        $availablePlayers = $this->playersService->getPlayersAvailableByLadderId($ladder);
        foreach ($groups as $group) {
            $statistics[$group->id] = $this->groupService->getStatistics($group->id, (bool)$ladder->isSingle);
        }
        $newOrderPlayers = $this->playersService->getNewOrder($players, $groups, $statistics);

        return view('ladders/duplicate', [
            'next' => true,
            'ladder' => $ladder,
            'groups' => $groups,
            'players' => $newOrderPlayers,
            'available' => $availablePlayers,
            'statistics' => $statistics,
            'links' => [],
            'navigationLinks' => $this->getNavigationLinks(),
        ]);
    }

    public function saveDuplicate(Request $request): RedirectResponse {
        $ladder = $this->ladderService->findById((int)$request->post('duplicate-ladder-id'));

        $validator = Validator::make($request->all(), [
            'ladder-name' => 'required|string|min:10|max:64',
            'ladder-date' => 'required|date_format:Y-m-d|size:10',
            'players-list' => ['required', new PlayersArray($ladder)],
        ], [
            'ladder-name.required' => 'A Name is required',
            'ladder-name.min' => 'Ladder Name cannot be smaller than 10 characters',
            'ladder-name.max' => 'Ladder Name cannot be greater than 64 characters',
            'ladder-date.required' => 'A Date is required',
            'ladder-date.date_format' => 'Format is not correct. Must be: YYYY-MM-DD',
        ]);
        //dd($request->post(), $validator->fails(), $validator->errors());
        $validator->validate();

        if ($validator->fails()) {
            return redirect()->route('duplicate.ladder', ['ladderID' => $request->post('duplicate-ladder-id')])
                ->withErrors($validator)
                ->withInput();
        }

        $params = $request->post();
        $params['ladder-is-single'] = (bool)$ladder->isSingle;
        $result = $this->ladderService->duplicateLadder($params);

        return redirect()->route('view.ladder', ['ladderID' => $result['ladder']->id]);
    }
}
