<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'sector_id',
        'mac_address',
        'status',
        'irrigation_state',
        'ai_status'
    ];

    /**
     * Relasi table device ke sector
     * Many to One
     * @return BelongsTo
     * */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    /**
     * Relation table device ke irrigation history
     * One to Many
     * @return HasMany
     * */
    public function irrigationHistories(): HasMany
    {
        return $this->hasMany(IrrigationHistories::class, 'device_id');
    }
}
