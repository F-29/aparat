<?php

namespace App\Policies;

use App\User;
use App\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function setState(User $user, Video $video = null)
    {
        return $user->isAdmin();
    }
}
