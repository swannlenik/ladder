<?php

namespace App\Models;

use App\Constants\Roles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccessRights extends Model
{
    protected $table = 'access_rights';
    protected $fillable = ['userId', 'pageName', 'writeAccess'];
    protected $primaryKey = ['userId', 'pageName'];
    public $incrementing = false;
    public $timestamps = false;
    use HasFactory;

    public static function createByRole(int $userID, string $role): array {
        $rights = [];
        DB::delete('DELETE FROM access_rights WHERE userId = ?', [$userID]);
        $roles = Roles::ROLES[$role];
        foreach ($roles as $role) {
            $rights[] = AccessRights::create([
                'userId' => $userID,
                'pageName' => strtolower($role),
                'writeAccess' => 1,
            ]);
        }
        return $rights;
    }
}
