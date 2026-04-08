<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IrrigationHistories extends Model
{
    //

    /**
     * Relasi table device ke sector
     * Many to One
     * @return BelongsTo
     * */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
