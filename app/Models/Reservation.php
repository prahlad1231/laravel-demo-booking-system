<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Carbon\CarbonInterface;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $space_id
 * @property int $user_id
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int $capacity_used
 * @property ReservationStatus $status
 * @property-read Space $space
 * @property-read User $user
 * @property-read Collection<int, AddOn> $addOns
 */
class Reservation extends Model {
    /** @use HasFactory<ReservationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['space_id', 'user_id', 'starts_at', 'ends_at', 'capacity_used', 'status'];

    protected function casts(): array {
        return ['status' => ReservationStatus::class, 'starts_at' => 'datetime', 'ends_at' => 'datetime'];
    }

    public function space(): BelongsTo {
        return $this->belongsTo(Space::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function addOns(): BelongsToMany {
        return $this->belongsToMany(AddOn::class)
            ->using(AddOnReservation::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    #[Scope]
    protected function active(Builder $query): void {
        $query->whereIn('status', [ReservationStatus::Confirmed, ReservationStatus::Pending]);
    }

    #[Scope]
    protected function overlapping(Builder $query, CarbonInterface $startsAt, CarbonInterface $endsAt): void {
        $query->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt);
    }
}
