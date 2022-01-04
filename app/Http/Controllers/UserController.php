<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find($request->user()->id);

        return new UserResource($user->load('orders'));
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
            if (File::exists('storage/images/users/' . $user->image)) :
                File::delete('storage/images/users/' . $user->image);
            endif;
            $imageName = $request->user()->id . '.' . $image->extension();
            $inter = Image::make($image);
            $inter->fit(200);
            $inter->save('storage/images/users/' . $imageName);
            $data['image'] = $imageName;
        endif;
        if ($request->get('birth_date') != null) :
            $data['birth_date'] = Carbon::parse($data['birth_date'])->setTimezone('Asia/Tashkent')->toDateString();
        endif;

        $user->update($data);

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
