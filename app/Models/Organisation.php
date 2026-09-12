<?php

namespace App\Models;

use App\Enums\OrganisationType;
use Database\Factories\OrganisationFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

// Carbon is like java's LocalDateTime
/**
 * @property int $id
 * @property OrganisationType $type
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Space> $spaces
 * @property-read Collection<int, AddOn> $addOns
 * @property-read Collection<int, Reservation> $reservations
 */
class Organisation extends Model {
    /** @use HasFactory<OrganisationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type'];

    protected function casts(): array {
        return ['type' => OrganisationType::class];
    }

    public function spaces(): HasMany {
        return $this->hasMany(Space::class);
    }

    public function addOns(): HasMany {
        return $this->hasMany(AddOn::class);
    }

    public function reservations(): HasManyThrough {
        return $this->hasManyThrough(Reservation::class, Space::class);
    }
}
