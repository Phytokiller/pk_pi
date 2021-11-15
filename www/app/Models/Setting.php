<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    
    use HasFactory;

    protected $casts = [
        'offset_t1' => 'float',
        'offset_t2' => 'float',
    ];

    public function account()
    {
        return $this->hasOne(Account::class);
    }

}