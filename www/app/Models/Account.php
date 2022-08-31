<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'bath_duration',
        'bath_temperature',
        'bath_number_prefix',
        'default_palettes_selected',
        'bath_counter',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function palettes()
    {
        return $this->hasMany(Palette::class);
    }

    public function baths()
    {
        return $this->hasMany(Bath::class);
    }

}