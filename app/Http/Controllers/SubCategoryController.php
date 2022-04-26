<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\TitleRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(SubCategory::class);
    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TitleRequest $request, Category $category)
    {
        $data = $request->validated();

        $subCategory = $category->subCategories()->create($data);

        return new SubCategoryResource($subCategory);
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
    public function update(TitleRequest $request, SubCategory $subCategory)
    {
        $data = $request->validated();

        $subCategory->update($data);

        return new SubCategoryResource($subCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        $deleted = $subCategory->delete();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
