<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'rajaongkir_districts';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['id', 'city_id', 'name', 'name_lower', 'zip_code'];
}
