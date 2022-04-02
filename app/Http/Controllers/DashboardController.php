<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Constants\Routes;
use App\Http\Services\AccessRightsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View {
        $user = auth()->user();
        $ars = app()->make(AccessRightsService::class);
        $accessRights = $ars->getRightsByUserId($user->getAuthIdentifier());
        $routes = Routes::Routes;


        return view('dashboard', [
            'accessRights' => $accessRights,
            'routes' => $routes,
        ]);
    }
}
