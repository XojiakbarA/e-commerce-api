<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\StoreRequest;
use App\Http\Resources\ReviewResource;
use App\Models\User;

class UserReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $user)
    {
        $data = $request->validated();

        $review = $user->reviews()->create($data);

        return new ReviewResource($review);
    }
}
