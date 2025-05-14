<?php

// ✅ Seeder: PaymentMethodSeeder
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder {
    public function run(): void {
        foreach (["Bank Transfer", "Cash", "Credit Card"] as $method) {
            PaymentMethod::create(['name' => $method]);
        }
    }
}