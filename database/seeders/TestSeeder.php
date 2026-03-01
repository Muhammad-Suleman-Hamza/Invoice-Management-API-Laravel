<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Database\Seeder;
use App\Enums\ContractStatus;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => 1,
        ]);

        Contract::create([
            'tenant_id' => 1,
            'unit_name' => 'Unit A',
            'customer_name' => 'John Doe',
            'rent_amount' => 1000,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => ContractStatus::Active,
        ]);
    }
}
