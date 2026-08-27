<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $table = 'rajaongkir_cities';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['id', 'province_id', 'name', 'name_lower', 'zip_code'];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'city_id', 'id');
    }
}
