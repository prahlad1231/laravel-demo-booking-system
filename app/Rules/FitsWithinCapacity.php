<?php

namespace App\Rules;

use App\Models\Space;
use Carbon\CarbonInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class FitsWithinCapacity implements ValidationRule {
    public function __construct(
        private readonly Space $space,
        private ?CarbonInterface $startsAt,
        private ?CarbonInterface $endsAt,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if ($this->startsAt === null || $this->endsAt === null) {
            return;
        }

        $booked = $this->space->reservations()
            ->active()
            ->overlapping($this->startsAt, $this->endsAt)
            ->sum('capacity_used');

        $remaining = $this->space->capacity - $booked;

        if ((int) $value > $remaining) {
            $fail("Only {$remaining} place(s) remain for this time.");
        }
    }
}
