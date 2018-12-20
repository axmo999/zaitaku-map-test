<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'facility_name',
        'home_care',
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
        'available_time_mon',
        'available_time_tue',
        'available_time_wed',
        'available_time_thu',
        'available_time_fri',
        'available_time_sat',
        'available_time_sun',
        'person',
        'correspondence_dept',
        'correspondence_time',
        'open_24hours',
        'foreign_language',
        'related_facilities',
        'options',
        'note',
        'publish',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function facility_type()
    {
        return $this->belongsTo('App\M_facility_type');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }
}
