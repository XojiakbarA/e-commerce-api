<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\UserImage  $userImage
     * @return \Illuminate\Http\Response
     */
    public function show(UserImage $userImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserImage  $userImage
     * @return \Illuminate\Http\Response
     */
    public function edit(UserImage $userImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserImage  $userImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserImage $userImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserImage  $userImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Storage::delete('public/images/users/' . $user->image->src);

        $user->image()->delete();

        $user->refresh();

        return new UserResource($user);
    }
}
