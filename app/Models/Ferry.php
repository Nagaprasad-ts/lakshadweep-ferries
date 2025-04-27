<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FerryCategory;

class Ferry extends Model
{
    protected $fillable = ['ferry-name', 'image', 'from', 'to'];

    public function categories()
    {
        return $this->belongsToMany(FerryCategory::class, 'category_ferry_pivots')
                    ->withTimestamps()
                    ->withPivot('id'); // You can add 'price' if you store price in pivot
    }
}
