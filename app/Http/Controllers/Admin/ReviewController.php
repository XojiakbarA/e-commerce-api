<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Filters\ReviewFilter;
use App\Http\Requests\Admin\PublishedRequest;
use App\Http\Requests\ReviewFilterRequest;
use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReviewFilterRequest $request)
    {
        $query = $request->all();
        $count = $request->query('count') ?? 9;
        $filter = app()->make(ReviewFilter::class, ['queryParams' => array_filter($query)]);

        $reviews = Review::filter($filter)->latest()->paginate($count);

        return ReviewResource::collection($reviews);
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
    public function update(PublishedRequest $request, Review $review)
    {
        $data = $request->validated();

        $updated = $review->update($data);

        return $updated;
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
