<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'address',
        'description',
        'price',
        'rooms',
        'surface',
        'bedrooms',
        'floor',
        'city',
        'postal_code',
        'sold'  
    ];
   
    protected $casts=[
    'sold' => 'boolean',
    ];
    
       public function options(): BelongsToMany{
        return $this->belongsToMany(Option::class);
       }

       public function getSlug(): string
       {
           return \Illuminate\Support\Str::slug($this->title);
       }

       public function scopeAvailable(Builder $builder, bool $available = true): Builder{
        return $builder->where('sold', !$available);
       }

       public function scopeRecent(Builder $builder): Builder{
        return $builder->orderBy('created_at', 'desc');
       }
} 
