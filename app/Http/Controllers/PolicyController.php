<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePolicyRequest;
use App\Http\Requests\UpdatePolicyRequest;
use App\Http\Resources\PolicyCollection;
use App\Http\Resources\PolicyResource;
use App\Models\Group;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class PolicyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Policy::class,'policy');
    }
    public function index(Request $request){
        return new PolicyCollection(Policy::all());

    }

    public function show(Request $request,Policy $policy){
        return (new PolicyResource($policy))->load('groups')->load('categories');
    }

    public function store(StorePolicyRequest $request){
        $validated=$request->validated();

        $user=JWTAuth::parseToken()->authenticate();
        $policy=$user->policies()->create($validated);

        $policy->categories()->attach($request->category_id);
        $policy->groups()->attach($request->group_id);
        return new PolicyResource($policy);
    }

    public function update(UpdatePolicyRequest $request,Policy $policy){
        $validated=$request->validated();
        $policy->update($validated);

        return new PolicyResource($policy);

    }
    public function destroy(Request $request,Policy $policy){
        $policy->delete();
        return response()->noContent();
    }

    public function setCategories(Request $request, $id){
        $policy=Policy::find($id);

        $categoryId=$request->category_id;

        if($policy->categories->contains($categoryId)){
            return response()->json([
                'message' => 'category is already present'
            ]);
        }
        
        
        $policy->categories()->attach($categoryId);
        //$policy->groups()->attach([$request->group_id]);

        return response()->json([
            'message' => 'category  assigned to policy'
        ]);
    }

    public function assignGroups(Request $request,$id ){
        $policy=Policy::find($id);
        $groupId=$request->group_id;
        if($policy->groups->contains($groupId)){
            return response()->json([
                'message' => 'group is already present'
            ]);
        }



        $policy->groups()->attach($groupId);

        return response()->json([
            'message'=>'groups added'
        ]);
    }
}