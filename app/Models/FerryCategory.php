<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ferry;

class FerryCategory extends Model
{
    protected $table = 'ferry_categories';

    protected $fillable = ['name', 'price'];

    public function ferries()
    {
        return $this->belongsToMany(Ferry::class, 'category_ferry_pivots')
                    ->withTimestamps()
                    ->withPivot('id'); // Add 'price' if it’s on pivot instead
    }
}
