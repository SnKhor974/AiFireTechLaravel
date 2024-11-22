<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FE extends Model
{
    protected $table = 'fire_extinguisher';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fe_location',
        'fe_serial_number',
        'fe_type',
        'fe_brand',
        'fe_man_date',
        'fe_exp_date',
        'fe_user_id',
    ];
}
