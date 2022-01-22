<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\AccessRights;
use Illuminate\Support\Collection;

class AccessRightsService
{
    private const PAGE_NAME_FOR_LINKS = [
        'view.all.ladders' => 'View Ladders',
        'view.players' => 'View Players',
        'view.users' => 'View Users',
        'player.statistics' => 'My Statistics',
    ];

    public function getRightsByUserId(int $userID): array {
        $access = [];
        $routes = AccessRights::where('userId', '=', $userID)->get();
        foreach ($routes as $route) {
            $access[$route->pageName] = $route->writeAccess;
        }
        return $access;
    }

    public function getLinksByUserId(int $userID): array {
        $access = [];
        $routes = AccessRights::where('userId', '=', $userID)
            ->whereIn('pageName', array_keys(self::PAGE_NAME_FOR_LINKS))
            ->get();

        foreach ($routes as $route) {
            $access[] = [
                'routeName' => $route->pageName,
                'writeAccess' => $route->writeAccess,
                'title' => self::PAGE_NAME_FOR_LINKS[$route->pageName],
            ];
        }
        return $access;
    }

    public function hasAccessToPage(int $userID, string $pageName): ?AccessRights {
        return AccessRights::where('userId', '=', $userID)->where('pageName', '=', $pageName)->first();
    }

    public function hasAccessToPages(int $userID, array $pageNameList): array {
        $listAccess = [];
        $accessRights = AccessRights::where('userId', '=', $userID)->whereIn('pageName', $pageNameList)->get();
        foreach ($accessRights as $ar) {
            $listAccess[$ar->pageName] = $ar->writeAccess === 1 ? 'RW' : 'RO';
        }
        return $listAccess;
    }
}
