<?php

namespace App\Services;

use App\Http\Requests\User\UpdateRequest;
use App\Models\Image;
use App\Models\User;
use App\Services\Traits\ImageConvertable;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class UserService
{
    use ImageConvertable;

    protected $dir = 'storage/images/users/';
    protected $width = 300;
    protected $height = 300;

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->safe()->except('image');
        $image = $request->file('image');

        if ($request->get('birth_date') != null) :
            $data['birth_date'] = Carbon::parse($data['birth_date'])->setTimezone('Asia/Tashkent')->toDateString();
        endif;

        $user->update($data);

        if ($image) :
            $path = $this->dir . $image->hashName();
            $src = $this->convertSaveImage($image, $path, $this->width, $this->height);

            if ($user->image) :
                $user->image()->update(['src' => $src]);
                File::delete($user->image->src);
            else :
                $user->image()->create(['src' => $src]);
            endif;
        endif;

        $user->refresh();

        return $user;
    }

    public function imageDestroy(User $user, Image $image)
    {
        File::delete($image->src);
        return $image->delete();
    }
}