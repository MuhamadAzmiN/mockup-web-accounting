<?php

namespace App\Traits;

trait AuthorizationChecker
{
    public function checkAuthorization($user, $permissions)
    {
        foreach ($permissions as $permission) {
            if (!$user->can($permission)) {
                abort(404);

            }
        }
    }
}
