<?php

declare(strict_types=1);


namespace App\Http\Services;


class LinksService
{
    protected AccessRightsService $accessRightsService;

    public function construct(AccessRightsService $accessRightsService) {
        $this->accessRightsService = $accessRightsService;
    }

    public function getLinks(int $userID): array {
        $links = [];
        $accessRights = $this->accessRightsService->getRightsByUserId($userID);

        return $links;
    }
}
