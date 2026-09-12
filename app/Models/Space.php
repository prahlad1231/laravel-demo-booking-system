<?php

namespace App\Models;

use Database\Factories\SpaceFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $organisation_id
 * @property string $name
 * @property int $capacity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Organisation $organisation
 * @property-read Collection<int, Reservation> $reservations
 */
class Space extends Model {
    /** @use HasFactory<SpaceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['organisation_id', 'name', 'capacity'];

    public function organisation(): BelongsTo {
        return $this->belongsTo(Organisation::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }
}
