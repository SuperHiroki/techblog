<?php

namespace App\Http\Controllers\MyPage;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Article;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => null,
                'public_email' => null,
                'github' => null,
                'website' => null,
                'organization' => null,
                'location' => null,
                'bio' => null,
                'sns1' => null,
                'sns2' => null,
                'sns3' => null,
                'sns4' => null,
                'sns5' => null,
                'sns6' => null,
            ]
        );

        return view('my-page.profile', compact('user', 'profile'));
    }
}
