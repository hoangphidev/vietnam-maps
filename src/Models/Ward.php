<?php

namespace HoangPhi\VietnamMap\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('vietnam-maps.tables.wards'));
    }

    public function district()
    {
        return $this->belongsTo(District::class, config('vietnam-maps.columns.district_id'), 'id');
    }
}
