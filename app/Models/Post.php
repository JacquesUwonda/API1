<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=[
        'title',
        'content',
        'user_id',
    ];
    // Relationship with User model
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
