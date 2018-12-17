<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_category extends Model
{
    //

    public function facilities()
    {
        return $this->hasMany('App/Facility');
    }
}
