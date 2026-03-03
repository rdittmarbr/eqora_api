<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::query()->firstOrCreate(
            ['code' => 'default'],
            [
                'name' => 'Tenant Default',
                'status' => 'active',
            ]
        );

        User::query()->updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'tenant_id' => $tenant->id,
            'name' => 'Admin User',
            'password' => 'password',
            'access_all' => true,
            'access_group' => null,
        ]);

        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'password' => 'password',
            'access_all' => true,
            'access_group' => null,
        ]);
    }
}
