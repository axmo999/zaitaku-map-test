<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'facility_name',
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
}
