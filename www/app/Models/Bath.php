<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Bath extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['account_id', 'user_id', 'number', 'finished_at'];

    protected $appends = ['created_at_formated'];

    protected $dates = ['finished_at'];

    public function getCreatedAtFormatedAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    public function account()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function palettes()
    {
        return $this->belongsToMany(Palette::class);
    }

    public function measures()
    {
        return $this->hasMany(BathMeasure::class);
    }

}