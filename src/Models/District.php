<?php

namespace HoangPhi\VietnamMap\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('vietnam-maps.tables.districts'));
    }

    public function province()
    {
        return $this->belongsTo(Province::class, config('vietnam-maps.columns.province_id'), 'id');
    }
    
    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
