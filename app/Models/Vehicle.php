<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicle';

    const UNIT_CUBIC_CM = 1;
    const UNIT_CUBIC_INCH = 2;

    protected $fillable = ['name', 'location', 'parent_category_id', 'position', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function scopeUnitCC(Builder $query)
    {
        return $query->where('displacement_unit', self::UNIT_CUBIC_CM);
    }

    public function scopeUnitCI(Builder $query)
    {
        return $query->where('displacement_unit', self::UNIT_CUBIC_INCH);
    }
}
