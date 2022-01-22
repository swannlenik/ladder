<?php

namespace App\Http\Controllers;

use App\Http\Services\AccessRightsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected array $links;
    protected array $navigationLinks;
    protected array $rankingClasses;

    protected function getCommonLinks(): array {
        return [
            [
                'name' => 'Players',
                'href' => route('view.players'),
            ],
            [
                'name' => 'Ladders',
                'href' => route('view.all.ladders'),
            ],
            [
                'name' => 'Logout',
                'href' => route('logout'),
            ],
        ];
    }

    protected function getNavigationLinks(int $userID = null): array {
        $user = auth()->user();
        $accessRightsService = app()->make(AccessRightsService::class);
        return $accessRightsService->getLinksByUserId($userID ?? $user->getAuthIdentifier());
    }

    protected function getRightsForRoute(int $userID, string $routeName): int {
        $user = auth()->user();
        $accessRightsService = app()->make(AccessRightsService::class);
        return $accessRightsService->getLinksByUserId($userID ?? $user->getAuthIdentifier());
    }
}
