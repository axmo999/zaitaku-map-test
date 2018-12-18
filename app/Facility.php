<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'facility_name',
        'facility_type_id',
        'postal_code',
        'prefecture_name',
        'city_name',
        'address',
        'latitude',
        'longitude',
        'telphone',
        'fax',
        'representative',
        'homepage',
    ];

/*     public function category()
    {
        return $this->belongsTo('App\M_category');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    } */
}
