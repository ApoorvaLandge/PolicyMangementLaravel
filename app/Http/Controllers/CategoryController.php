<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Category::class,'category');
    }
    public function index(Request $request){
        return new CategoryCollection(Category::all());

    }

    public function show(Request $request,Category $category){
        return (new CategoryResource($category))->load('policies');
    }

    public function store(StoreCategoryRequest $request){
        $validated=$request->validated();
        $user=JWTAuth::parseToken()->authenticate();
        $category=$user->categories()->create($validated);
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request,Category $category){
        $validated=$request->validated();
        $category->update($validated);

        return new CategoryResource($category);

    }
    public function destroy(Request $request,Category $category){
        $category->delete();
        return response()->noContent();
    }
}
