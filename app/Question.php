<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'key' ,'type', 'group','data', 'user_id', 'created_at', 'updated_at'];
     
 
    public function answers()
    {
        return $this->hasMany('App\Answer')->select(['id', 'title']);
    }
}
