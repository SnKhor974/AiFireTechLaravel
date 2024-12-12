<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeBrands extends Model
{
    protected $table = 'fe_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'short'
    ];
}
