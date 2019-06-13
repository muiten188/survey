<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
     protected $fillable = ['title', 'question_id', 'user_id', 'correct', 'created_at', 'updated_at'];
}
