<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EditUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, User $user)
    {
        $data = $request->validated();
        $image = $request->file('image');

        if ($image != null) :
            $path = 'storage/images/users/';
            $imageName = $image->hashName();
            $src = $path . $imageName;

            Image::makeImage($image, $src, 300, 300);

            if ($user->image) :
                Storage::delete('public/images/users/' . $user->image->src);
                $user->image()->update(['src' => $imageName]);
            else :
                $user->image()->create(['src' => $imageName]);
            endif;
        endif;

        if ($request->get('birth_date') != null) :
            $data['birth_date'] = Carbon::parse($data['birth_date'])->setTimezone('Asia/Tashkent')->toDateString();
        endif;

        unset($data['image']);

        $user->update($data);

        $user->refresh();

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
