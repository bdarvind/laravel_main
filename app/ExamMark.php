<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'right_questions'
    ];
}
