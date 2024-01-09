<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\DoctorSpecialization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorSpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (DoctorSpecialization::cases() as $item) {
            DB::table('doctor_specializations')->insert([
                'name' => $item->value,
                'created_at' => now(),
            ]);
        }
    }
}
