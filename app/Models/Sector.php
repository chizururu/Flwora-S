<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectors';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'devices_count',
        'user_id'
    ];

    /**
     * Relation table sector ke device
     * One to Many
     * @return HasMany
     * */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'sector_id');
    }
}
