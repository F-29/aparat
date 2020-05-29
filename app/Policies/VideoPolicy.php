<?php

namespace App\Policies;

use App\RepublishVideo;
use App\User;
use App\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function setState(User $user)
    {
        return $user->isAdmin();
    }

    public function republish(User $user, Video $video)
    {
        return !empty($video) &&
            (
                $video->user_id !== $user->id &&
                RepublishVideo::where(['user_id' => $user->id, 'video_id' => $video->id])->count() === 0
            );
    }
}
