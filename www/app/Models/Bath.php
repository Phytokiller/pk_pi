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

    public $timestamps = false;

    protected $fillable = ['account_id', 'user_id', 'number', 'finished_at', 'created_at', 'updated_at'];

    protected $appends = ['created_at_formated'];

    //protected $dates = ['created_at', 'updated_at', 'finished_at'];

    protected $casts = [
        'created_at' => 'date:Y-m-d\TH:i',
        'updated_at' => 'date:Y-m-d\TH:i',
        'finished_at' => 'date:Y-m-d\TH:i',
    ];

    public function getCreatedAtFormatedAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y à H:i');
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