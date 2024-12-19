<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FE extends Model
{
    protected $table = 'fire_extinguisher';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'fe_location',
        'fe_serial_number',
        'fe_type',
        'fe_brand',
        'fe_man_date',
        'fe_exp_date',
        'fe_user_id',
        '1st_year',
        '2nd_year',
        '3rd_year',
        '4th_year',
        '5th_year',
        '6th_year',
        '7th_year',
        '8th_year',
        '9th_year',
        '10th_year'
    ];
}
