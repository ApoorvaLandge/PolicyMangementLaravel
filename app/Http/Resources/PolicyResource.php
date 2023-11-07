<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        ['id'=>$this->id,
        'title'=>$this->title,
        'creator_id'=>$this->creator_id,
        'groups'=>GroupResource::collection($this->whenLoaded('groups')),
        'category'=>CategoryResource::collection($this->whenLoaded('categories')),
        'created_at'=>$this->created_at,
        'updated_at'=>$this->updated_at,];
    }
}
