<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'facility_id',
        'question_cd',
        'answer_content',
    ];

    public function Facility()
    {
        return $this->belongsTo('App\Facility');
    }
}

