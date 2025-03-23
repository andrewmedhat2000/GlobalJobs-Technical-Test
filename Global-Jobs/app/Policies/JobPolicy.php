<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function delete(User $user, Job $job)
    {
        return $user->is_admin;
    }
}
