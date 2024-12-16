<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaInCharge extends Model
{
    protected $table = 'area_in_charge';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'area_id',
    ];
}
