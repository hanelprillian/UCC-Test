<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicle';

    const UNIT_CUBIC_CM = 1;
    const UNIT_CUBIC_INCH = 2;

    protected $fillable = ['name', 'location', 'bore', 'stroke','cylinders','engine_displacement','engine_power','price','displacement_unit'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['displacement_unit_name','bore_unit_name','stroke_unit_name'];

    public function scopeUnitCC(Builder $query)
    {
        return $query->where('displacement_unit', self::UNIT_CUBIC_CM);
    }

    public function scopeUnitCI(Builder $query)
    {
        return $query->where('displacement_unit', self::UNIT_CUBIC_INCH);
    }

    public function getDisplacementUnitNameAttribute()
    {
        return $this->attributes['displacement_unit'] == self::UNIT_CUBIC_CM ? 'Cubic Centimeter' : 'Cubic Inches';
    }

    public function getBoreUnitNameAttribute()
    {
        return $this->attributes['displacement_unit'] == self::UNIT_CUBIC_CM ? 'Millimeter' : 'Inches';
    }

    public function getStrokeUnitNameAttribute()
    {
        return $this->attributes['displacement_unit'] == self::UNIT_CUBIC_CM ? 'Millimeter' : 'Inches';
    }
}
