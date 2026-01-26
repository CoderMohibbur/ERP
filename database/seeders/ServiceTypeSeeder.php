<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['key' => 'shared_hosting', 'name' => 'Shared Hosting', 'sort_order' => 10],
            ['key' => 'dedicated',      'name' => 'Dedicated Server / VPS', 'sort_order' => 20],
            ['key' => 'domain',         'name' => 'Domain', 'sort_order' => 30],
            ['key' => 'ssl',            'name' => 'SSL Certificate', 'sort_order' => 40],
            ['key' => 'maintenance',    'name' => 'Maintenance / Support', 'sort_order' => 50],
        ];

        foreach ($types as $t) {
            $row = ServiceType::withTrashed()->firstOrNew(['key' => $t['key']]);
            $row->fill([
                'name' => $t['name'],
                'description' => $t['description'] ?? null,
                'is_active' => true,
                'sort_order' => $t['sort_order'] ?? 0,
            ]);
            if ($row->trashed()) {
                $row->restore();
            }
            $row->save();
        }
    }
}
