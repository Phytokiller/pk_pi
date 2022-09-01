<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BathPalette extends Pivot
{
    public function bath()
    {
        return $this->belongsTo(Bath::class);
    }

    public function palette()
    {
        return $this->belongsTo(Palette::class);
    }
}
