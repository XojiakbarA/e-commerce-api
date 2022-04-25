<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserImageController extends Controller
{
    public function destroy(User $user, Image $image)
    {
        Storage::delete('public/images/users/' . $image->src);

        $deleted = $image->deleteOrFail();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
