<?php

namespace App\Http\Requests\Concerns;

trait AuthorizesWithAnyPermission
{
    protected function userCanAny(array $permissions): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        foreach ($permissions as $perm) {
            if ($user->can($perm)) {
                return true;
            }
        }

        return false;
    }
}
