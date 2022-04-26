<?php

namespace App\Http\Controllers;

use App\Http\Filters\UserFilter;
use App\Http\Requests\FilterRequest\UserFilterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserFilterRequest $request)
    {
        $query = $request->validated();
        $count = $request->query('count') ?? 9;
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($query)]);

        $users = User::filter($filter)->latest()->paginate($count);

        return UserResource::collection($users);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
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
    public function destroy(User $user)
    {
        //
    }
}
