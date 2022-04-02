<?php

declare(strict_types=1);


namespace App\Constants;


use Illuminate\View\View;

class Roles
{
    const ROLE_PLAYER = [
        'dashboard',
        'view.all.ladders',
        'view.group',
        'view.ladder',
        'player.statistics',
    ];
    const ROLE_GROUP_EDITOR = [
        'dashboard',
        'view.all.ladders',
        'view.ladder',
        'view.group',
        'create.group',
        'create.groups',
        'save.group',
        'save.groups',
        'delete.group',
        'save.game',
        'save.double.game',
        'update.double.game',
        'update.game',
        'delete.game',
        'delete.double.game',
        'view.players',
        'create.player',
        'save.player',
        'delete.player',
        'available.players',
        'set.all.available.players',
        'set.available.players',
        'ladder.ranking',
    ];
    const ROLE_LADDER_EDITOR = [
        'dashboard',
        'view.all.ladders',
        'view.ladder',
        'create.ladder',
        'delete.ladder',
        'duplicate.ladder',
        'next.ladder',
        'view.group',
        'create.group',
        'create.groups',
        'save.group',
        'save.groups',
        'delete.group',
        'save.game',
        'save.double.game',
        'save.duplicate.ladder',
        'delete.game',
        'delete.double.game',
        'update.double.game',
        'update.game',
        'view.players',
        'create.player',
        'save.player',
        'delete.player',
        'available.players',
        'set.all.available.players',
        'set.available.players',
        'ladder.ranking',
    ];
    const ROLE_ADMIN = [
        'available.players',
        'create.group',
        'create.groups',
        'create.ladder',
        'create.player',
        'dashboard',
        'delete.group',
        'delete.ladder',
        'delete.player',
        'duplicate.ladder',
        'manage.users',
        'next.ladder',
        'player.statistics',
        'save.double.game',
        'save.duplicate.ladder',
        'save.game',
        'save.group',
        'save.groups',
        'set.all.available.players',
        'set.available.players',
        'update.double.game',
        'update.game',
        'update.user',
        'view.all.ladders',
        'view.group',
        'view.ladder',
        'view.players',
        'ladder.ranking',
        'delete.game',
        'delete.double.game',
    ];

    const ROLES = [
        'ROLE_PLAYER' => self::ROLE_PLAYER,
        'ROLE_GROUP_EDITOR' => self::ROLE_GROUP_EDITOR,
        'ROLE_LADDER_EDITOR' => self::ROLE_LADDER_EDITOR,
        'ROLE_ADMIN' => self::ROLE_ADMIN,
    ];
}
