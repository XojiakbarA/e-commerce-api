<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\StoreRequest;
use App\Http\Resources\ReviewResource;
use App\Models\User;

class UserReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('own_resource');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $reviews = $user->reviews()->paginate(5);

        return ReviewResource::collection($reviews);
    }
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
