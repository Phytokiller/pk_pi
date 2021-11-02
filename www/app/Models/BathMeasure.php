<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BathMeasure extends Model
{

    use Uuids;
    use HasFactory;

    protected $fillable = ['bath_id', 'sensor_1', 'sensor_2', 'door', 'boiler', 'elapsed_time'];

    public function bath()
    {
        return $this->belongsTo(Bath::class);
    }

}
