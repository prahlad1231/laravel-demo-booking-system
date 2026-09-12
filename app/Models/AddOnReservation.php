<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property int $reservation_id
 * @property int $add_on_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AddOn $addOn
 * @property-read Reservation $reservation
 */
class AddOnReservation extends Pivot {
    protected $table = 'add_on_reservation';

    protected $fillable = ['quantity'];

    public function addOn(): BelongsTo {
        return $this->belongsTo(AddOn::class);
    }

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }
}
