<?php

// ✅ Seeder: PaymentSeeder
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $invoice = Invoice::first();
        $method = PaymentMethod::first();

        foreach (range(1, 5) as $i) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'payment_method_id' => $method->id,
                'amount' => $faker->randomFloat(2, 100, 1000),
                'payment_date' => now(),
                'note' => $faker->sentence,
            ]);
        }
    }
}
