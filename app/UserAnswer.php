<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = ['user_id','question_id','question','answer','group', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
     
}
