<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    //

    protected $casts = [
        'question_list' => 'array',
    ];
}
