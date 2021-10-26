<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Palette extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id', 'account_id', 'number', 'created_at', 'updated_at', 'deleted_at'];

    public function baths()
    {
        return $this->belongsToMany(Bath::class);
    }

}