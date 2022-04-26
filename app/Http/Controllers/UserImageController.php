<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(Image::class);
    }

    public function destroy(Request $request, User $user, Image $image)
    {
        if ($user->id !== $image->imageable_id || ($user->id !== $request->user()->id && !$request->user()->isAdmin())) :
            abort(403, 'Forbidden');
        endif;

        Storage::delete('public/images/users/' . $user->image->src);

        $deleted = $user->image->deleteOrFail();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
