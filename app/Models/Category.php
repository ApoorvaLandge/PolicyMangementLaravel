<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function policies(): BelongsToMany {
        return $this->belongsToMany(Policy::class,PolicyCategory::class);
    }

    // protected static function booted(): void{
    //     static::addGlobalScope('creator',function(Builder $builder){
    //         $builder->where('creator_id',Auth::id());

    //     });
    // }

}