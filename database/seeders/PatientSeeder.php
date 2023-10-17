<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::factory(10)
            ->for(Owner::factory(), 'owner')
            ->create();
    }
}
