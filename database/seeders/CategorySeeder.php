<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name_en' => 'doctor', 'name_ar' => 'دكتور'],
            ['name_en' => 'pharmacy', 'name_ar' => 'صيدلية'],
            ['name_en' => 'laboratory', 'name_ar' => 'مختبر'],
            ['name_en' => 'clinic', 'name_ar' => 'عيادة'],
        ];

        foreach ($categories as $item) {
            DB::table('categories')->insert([
                'name_en' => $item['name_en'],
                'name_ar' => $item['name_ar'],
                'created_at' => now(),
            ]);
        }
    }
}
