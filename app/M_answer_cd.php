<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_answer_cd extends Model
{
    protected $fillable = [
        'answer_group_cd',
        'answer_cd',
        'answer_content',
    ];

    public function answer()
    {
        return $this->hasMany('App\Answer', 'answer_cd');
    }
}
