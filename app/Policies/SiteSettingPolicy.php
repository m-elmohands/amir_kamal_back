<?php

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;

class SiteSettingPolicy
{
    public function before(User $user): ?bool
    {
        return $user->is_admin ? true : null;
    }

    public function view(User $user, SiteSetting $settings): bool
    {
        return false;
    }

    public function update(User $user, SiteSetting $settings): bool
    {
        return false;
    }
}
