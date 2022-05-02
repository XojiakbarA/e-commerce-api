<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserImageController extends Controller
{
    protected $service;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth:sanctum');

        $this->service = $userService;
    }

    public function destroy(Request $request, User $user, Image $image)
    {
        if ($user->id !== $request->user()->id && !$request->user()->isAdmin()) :
            abort(403, 'Forbidden');
        endif;
        if ($user->id !== $image->imageable_id) :
            abort(404, 'Not Found');
        endif;

        $deleted = $this->service->imageDestroy($user, $image);

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
