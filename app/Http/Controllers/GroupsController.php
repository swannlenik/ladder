<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Http\Services\AccessRightsService;
use App\Http\Services\GroupService;
use App\Http\Services\LadderService;
use App\Http\Services\PlayersService;
use App\Http\Services\ResultsService;
use App\Http\Services\SetsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class GroupsController extends Controller
{
    private AccessRightsService $accessRightsService;
    private PlayersService $playersService;
    private GroupService $groupService;
    private ResultsService $resultsService;
    private LadderService $ladderService;
    private SetsService $setsService;

    public function __construct(
        AccessRightsService $accessRightsService,
        PlayersService $playersService,
        GroupService $groupService,
        ResultsService $resultsService,
        LadderService $ladderService,
        SetsService $setsService
    ) {
        $this->accessRightsService = $accessRightsService;
        $this->playersService = $playersService;
        $this->groupService = $groupService;
        $this->resultsService = $resultsService;
        $this->ladderService = $ladderService;
        $this->setsService = $setsService;

        $this->rankingClasses = [
            1 => 'group__player-ranking--gold',
            2 => 'group__player-ranking--silver',
            3 => 'group__player-ranking--bronze',
        ];
        $this->links = $this->getCommonLinks();
    }

    public function view(int $groupId): View {
        $group = $this->groupService->getGroupById($groupId);
        $ladder = $this->ladderService->findById($group->ladderId);
        $groupsLinks = $this->groupService->getGroupsLinksByLadderId($group->ladderId);
        $listAccessRights = $this->accessRightsService->hasAccessToPages(auth()->user()->getAuthIdentifier(), ['update.game', 'update.double.game', 'create.group', 'create.groups']);

        if ($group->isSingle === 1) {
            $players = $this->playersService->getPlayersByGroupId($groupId);
            $games = $this->resultsService->getGamesByGroupId($groupId);
            $sets = $this->setsService->getSetsByGroupId($groupId);
        } else {
            $players = $this->playersService->getDoublePlayersByGroupId($groupId);
            $games = $this->resultsService->getDoubleGamesByGroupId($groupId);
            $sets = [];
        }
        $statistics = $this->groupService->getStatistics($groupId, (bool)$group->isSingle);

        $this->links = array_merge([
                'create.group' => [
                    'name' => 'Create 1 Group',
                    'href' => route('create.group', ['ladderID' => $group->ladderId]),
                    'class' => 'btn-green'
                ],
                'create.groups' => [
                    'name' => 'Create multiple Groups',
                    'href' => route('create.groups', ['ladderID' => $group->ladderId]),
                    'class' => 'btn-green'
                ],
            ],
        $groupsLinks,
        );
        if (!isset($listAccessRights['create.group']) || $listAccessRights['create.group'] !== 'RW') {
            unset($this->links['create.group']);
        }
        if (!isset($listAccessRights['create.groups']) || $listAccessRights['create.groups'] !== 'RW') {
            unset($this->links['create.groups']);
        }

        return \view('groups/view', [
            'ladder' => $ladder,
            'group' => $group,
            'players' => $players,
            'games' => $games,
            'sets' => $sets,
            'statistics' => $statistics,
            'links' => $this->links,
            'rankingClasses' => $this->rankingClasses,
            'navigationLinks' => $this->getNavigationLinks(),
            'accessRights' => $listAccessRights,
        ]);
    }

    public function create(int $ladderID): View {
        $ladder = $this->ladderService->findById($ladderID);
        $players = $this->playersService->getPlayersAvailableByLadderId($ladder);
        $links = array_merge($this->getCommonLinks(),
            [
                [
                    'name' => 'Back to ladder',
                    'href' => route('view.ladder', ['ladderID' => $ladderID]),
                ],
            ]
        );
        return view('groups/create', [
            'ladder' => $ladder,
            'players' => $players,
            'links' => $links,
            'navigationLinks' => $this->getNavigationLinks(),
       ]);
    }

    public function save(Request $request): RedirectResponse {
        $ladder = $this->ladderService->findById((int)$request->post('group-ladder-id'));
        $minimum = (bool)$ladder->isSingle ? '3' : '4';

        $validator = Validator::make($request->all(), [
            'players' => 'required|min:'.$minimum.'|max:5',
            'group-name' => 'required|string',
        ], [
            'players.min' => 'A minimum of '.$minimum.' players is required',
            'players.max' => 'A maximum of 5 players is allowed',
            'group-name.required' => 'A Group Name is required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('create.group', ['ladderID' => $request->post('group-ladder-id')])
                ->withErrors($validator)
                ->withInput();
        }

        $params = array_merge($validator->safe()->all(), ['group-ladder-id' => $ladder->id]);
        $isSingle = $ladder->isSingle === 1;
        $group = $this->groupService->createGroup($params, $isSingle);
        if ($isSingle) {
            $games = $this->resultsService->createGames($group, $params['players']);
            $sets = $this->setsService->createSets($games, $ladder);
        } else {
            $games = $this->resultsService->createDoubleGame($group, array_keys($params['players']));
        }

        return redirect()->route('view.ladder', ['ladderID' => $group->ladderId]);
    }

    public function delete(int $groupID): RedirectResponse {
        $ladderID = $this->groupService->deleteGroup($groupID);

        return redirect()->route('view.ladder', ['ladderID' => $ladderID]);
    }

    public function createMultiple(int $ladderID): View {
        $ladder = $this->ladderService->findById($ladderID);
        if ((bool)$ladder->isSingle) {
            $players = $this->playersService->getPlayersAvailableByLadderId($ladder);
        } else {
            $players = $this->playersService->getDoublePlayersAvailableByLadderId($ladderID);
        }

        return view('groups/createMultiple', [
            'links' => [],
            'ladder' => $ladder,
            'players' => $players,
            'navigationLinks' => $this->getNavigationLinks(),
        ]);
    }

    public function saveMultiple(Request $request): RedirectResponse {
        $unsortedPlayers = $request->post('player');
        $ladder = $this->ladderService->findById((int)$request->post('ladder-id'));
        $isSingle = (bool)$ladder->isSingle;

        $groups = $this->groupService->createMultipleGroups($unsortedPlayers, $ladder->id, $isSingle);
        $games = $this->resultsService->createMultipleGames($unsortedPlayers, $groups, $isSingle);

        return redirect()->route('view.ladder', ['ladderID' => $request->post('ladder-id')]);
    }
}
