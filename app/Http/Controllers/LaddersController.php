<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Http\Services\AccessRightsService;
use App\Http\Services\GroupService;
use App\Http\Services\LadderService;
use App\Http\Services\PlayersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $listAccessRights = $this->accessRightsService->hasAccessToPages(auth()->user()->getAuthIdentifier(), ['delete.ladder', 'create.ladder']);
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
        $groups = $this->groupService->getGroupsByLadderId($ladderID);
        foreach ($groups as $group) {
            if ($group->isSingle === 1) {
                $playersByGroup[$group->id] = $this->playersService->getPlayersByGroupId($group->id);
            } else {
                $playersByGroup[$group->id] = $this->playersService->getDoublePlayersByGroupId($group->id);
            }
            $statisticsByGroup[$group->id] = $this->groupService->getStatistics($group->id, (bool)$ladder->isSingle);
        }
        $this->links = [
            [
                'name' => 'Back to Ladder',
                'href' => route('view.ladder', ['ladderID' => $ladderID]),
            ]
        ];

        return view('ladders/rankings', [
            'ladder' => $ladder,
            'groups' => $groups,
            'statisticsByGroup' => $statisticsByGroup,
            'playersByGroup' => $playersByGroup,
            'links' => $this->links,
            'navigationLinks' => $this->navigationLinks,
            'accessRights' => $listAccessRights,
            'rankingClasses' => $this->rankingClasses,
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
}
