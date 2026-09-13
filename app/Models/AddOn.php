<?php

namespace App\Models;

use Database\Factories\AddOnFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int $organisation_id
 * @property string $name
 * @property int $price_cents
 * @property-read float $price
 * @property-read Organisation $organisation
 * @property-read Collection<int, Reservation> $reservations
 */
class AddOn extends Model {
    /** @use HasFactory<AddOnFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['organisation_id', 'name', 'price_cents'];

    protected function casts(): array {
        return ['price_cents' => 'integer'];
    }

    public function organisation(): BelongsTo {
        return $this->belongsTo(Organisation::class);
    }

    public function reservations(): BelongsToMany {
        return $this->belongsToMany(Reservation::class)
            ->using(AddOnReservation::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    protected function price(): Attribute {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => (float) ($attributes['price_cents'] / 100),
        );
    }
}
