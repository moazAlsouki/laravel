<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function getLikes(){
        return $this->belongsToMany(User::class,'likes');
    }
    public function getViews(){
        return $this->belongsToMany(User::class,'views');
    }
    public function getcomments(){
        return $this->hasMany('App\Models\Comment','product_id','id');
    }
}
