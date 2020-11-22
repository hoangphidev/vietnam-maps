<?php

namespace HoangPhi\VietnamMap\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('vietnam-maps.tables.provinces'));
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
