<?php

namespace App\View\Components;

use App\Http\Services\AccessRightsService;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{

    public function render(): View
    {
        /** @var AccessRightsService $ars */
        $ars = app()->make(AccessRightsService::class);
        $user = auth()->user();
        $navigationLinks = $ars->getLinksByUserId($user->getAuthIdentifier());

        return view('layouts.app', [
            'navigationLinks' => $navigationLinks
        ]);
    }
}
