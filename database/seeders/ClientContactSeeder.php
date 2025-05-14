<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientContact;
use App\Models\Client;
use Faker\Factory as Faker;

class ClientContactSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $client = Client::first();

        $contactTypes = ['email', 'phone', 'website'];

        foreach (range(1, 5) as $i) {
            ClientContact::create([
                'client_id' => $client->id,
                'type' => $type = $faker->randomElement($contactTypes),
                'value' => match ($type) {
                    'email' => $faker->safeEmail,
                    'phone' => $faker->phoneNumber,
                    'website' => $faker->url,
                },
            ]);
        }
    }
}
