<?php

namespace Database\Seeders;

use App\Enums\ReservationStatus;
use App\Models\AddOn;
use App\Models\Organisation;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        $attractions = Organisation::factory(3)->attraction()
            ->has(Space::factory(3))
            ->has(AddOn::factory(3))
            ->create();
        $schools = Organisation::factory(2)->school()->create();
        $tourOperators = Organisation::factory(2)->tourOperator()->create();

        $admin = User::factory()->admin()->create(['email' => 'admin@app.test']);
        $attractionOwner = User::factory()->owner()->recycle($attractions->first())
            ->create(['email' => 'owner@attraction.test']);
        $attractionAdmin = User::factory()->manager()->recycle($attractions->first())
            ->create(['email' => 'admin@attraction.test']);
        $attractionReceptionist = User::factory()->receptionist()->recycle($attractions->first())
            ->create(['email' => 'receptionist@attraction.test']);

        $tourOperatorAdmin = User::factory()->manager()->recycle($tourOperators->first())
            ->create(['email' => 'operator@touroperator.test']);

        $schoolUser = User::factory()->manager()->recycle($schools->first())
            ->create(['email' => 'teacher@school.test']);

        $individual1 = User::factory()->customer()->create(['email' => 'user@test.test']);
        $individualGroup = User::factory(4)->customer()->create();

        $bookers = $individualGroup->merge([$tourOperatorAdmin, $schoolUser, $individual1]);

        $reservations = Reservation::factory(20)
            ->recycle(Space::all())
            ->recycle($bookers)
            ->sequence(
                ['status' => ReservationStatus::Pending],
                ['status' => ReservationStatus::Confirmed],
                ['status' => ReservationStatus::Declined],
                ['status' => ReservationStatus::Cancelled],
            )
            ->create();

        $reservationSubset = $reservations->take(10);
        $reservationSubset->load('space.organisation.addOns');

        foreach ($reservationSubset as $reservation) {
            $addon = $reservation->space->organisation->addOns->random();
            $reservation->addOns()->attach($addon->id, ['quantity' => \rand(1, 3)]);
        }
    }
}
