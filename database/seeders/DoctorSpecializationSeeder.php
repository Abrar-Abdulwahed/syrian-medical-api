<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorSpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name_en' => 'dental',
                'name_ar' => 'أسنان'
            ],
            [
                'name_en' => 'optics',
                'name_ar' => 'بصريات'
            ],
            [
                'name_en' => 'nutritionist',
                'name_ar' => 'أخصائي تغذية'
            ],
            [
                'name_en' => 'home-nurse',
                'name_ar' => 'ممرض منزلي',
            ],
            [
                'name_en' => 'plastic-surgery',
                'name_ar' => 'جراح تجميل',
            ],
            [
                'name_en' => 'x-rays',
                'name_ar' => 'أشعة',
            ],
            [
                'name_en' => 'cosmetology',
                'name_ar' => 'تجميل',
            ],
        ];

        foreach ($specializations as $item) {
            DB::table('doctor_specializations')->insert([
                'name_en' => $item['name_en'],
                'name_ar' => $item['name_ar'],
                'created_at' => now(),
            ]);
        }
    }
}
