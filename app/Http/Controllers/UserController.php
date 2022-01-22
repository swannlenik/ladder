<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Constants\Roles;
use App\Http\Services\UsersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    protected UsersService $usersService;
    // 20e11e85: $2y$10$ikHMkbIdjpZzPSFyTJ3RFO0pPITvX07hmTQ4rFNhP6BxUlvT1JrFe

    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    public function view(int $userID = 0): View {
        $users = $this->usersService->getAllUsers();
        $user = $userID > 0 ? $this->usersService->findById($userID) : null;

        return view('users/view', [
            'links' => [],
            'navigationLinks' => $this->getNavigationLinks(),
            'roles' => Roles::ROLES,
            'users' => $users,
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request): RedirectResponse {
        $params = $request->post();
        $password = null;
        if (isset($params['user-password-1']) && isset($params['user-password-2']) && $params['user-password-1'] === $params['user-password-2']) {
            $password = Hash::make($params['user-password-1']);
        }
        $role = $params['user-role'] ?? 'ROLE_PLAYER';

        $this->usersService->updateUser((int)$params['user-id'], $role, $password);

        return redirect()->route('view.users', ['userID' => $params['user-id']]);
    }
}
