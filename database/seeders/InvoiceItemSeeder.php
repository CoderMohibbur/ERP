<?php

// ✅ Seeder: InvoiceItemSeeder
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\InvoiceItem;
use App\Models\Invoice;
use Faker\Factory as Faker;

class InvoiceItemSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $invoice = Invoice::first();

        foreach (range(1, 5) as $i) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $faker->sentence,
                'quantity' => rand(1, 5),
                'unit_price' => $faker->randomFloat(2, 100, 500),
            ]);
        }
    }
}