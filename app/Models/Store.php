<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    /** @use HasFactory<\Database\Factories\StoreFactory> */
    use SoftDeletes;
        protected $fillable = ['name', 'description', 'latitud', 'longitud', 'user_id'];
        public function products() { return $this->hasMany(Product::class); }
    use HasFactory;
}
