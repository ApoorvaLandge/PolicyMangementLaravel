<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Policy extends Model
{
    use HasFactory;
    protected $fillable =[
        'title',
    ];

    public function creator() : BelongsTo {
        return $this->belongsTo(User::class,'creator_id');
    }

    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class,PolicyCategory::class);
    }

    public function groups(): BelongsToMany{
        return $this->belongsToMany(Group::class,GroupPolicy::class);
    }

    // protected static function booted(): void{
    //     static::addGlobalScope('creator',function(Builder $builder){
    //         $builder->where('creator_id',Auth::id());

    //     });
    // }
}
