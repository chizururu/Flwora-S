<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IrrigationHistories extends Model
{

    use HasFactory;

    protected $table = 'irrigation_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'device_id',
        'type',
        'soil',
        'temperature',
        'humidity',
    ];

    /**
     * Relasi table device ke sector
     * Many to One
     * @return BelongsTo
     * */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
