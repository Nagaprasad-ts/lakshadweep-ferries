<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Enums\BookingLocation;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $i) {
            $guestName = $faker->name;
            $slug = Str::slug($guestName);

            // Randomly decide the state: either active OR paid
            $isPaid = $faker->boolean(50);
            $isActive = !$isPaid;

            Booking::create([
                'guest_name'   => $guestName,
                'slug'         => $slug,
                'location'     => $faker->randomElement(BookingLocation::cases())->value,
                'booking_date' => Carbon::now()->addDays(rand(1, 30))->format('Y-m-d'),
                'Adults'       => rand(1, 5),
                'Children'     => rand(0, 3),
                'Kids'         => rand(0, 2),
                'Infants'      => rand(0, 2),
                'price'        => rand(1000, 7500),
                'is_active'    => $isActive,
                'is_paid'      => $isPaid,
            ]);
        }
    }
}
