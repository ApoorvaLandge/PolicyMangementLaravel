<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Group::class,'group');
    }
    public function index(Request $request){
        return new GroupCollection(Group::all());

    }
    public function show(Request $request,Group $group){
        return (new GroupResource($group))
        ->load('members')
        ->load('policies');

    }

    public function store(StoreGroupRequest $request){
        $validated=$request->validated();
        $user=JWTAuth::parseToken()->authenticate();

        $group=$user->groups()->create($validated);
        $group->members()->attach([Auth::id()]);

        return new GroupResource($group);


    }

    public function update(UpdateGroupRequest $request,Group $group){
        $validated=$request->validated();

        $group->update($validated);

        return new GroupResource($group);
    }

    public function destroy(Request $request,Group $group){
        $group->delete();
        return response()->noContent();
    }

    public function addMember(Request $request, $id){
        $group=Group::find($id);

        if(! $group){
            return response()->json([
                'message'=>' Group not found'
            ],404);
        }

        $userId=$request->user_id;

        $user=User::find($userId);
        if($user->memberships()->count()> 0){
            // return response()->json([
            //     'message' => 'User is already has membership'
            // ],400);
            $currentGroup=$user->memberships()->first();
            $currentGroup->members()->detach($userId);
        }


        $group->members()->attach($userId);
    
        return response()->json([
            'message'=>'Member added'
        ]);
    }

    // public function assignPolicy(Request $request,$id ){
    //     $group=Group::find($id);
    //     $group->policies()->attach($request->policy_id);

    //     return response()->json([
    //         'message'=>'policy added'
    //     ]);
    // }


}
