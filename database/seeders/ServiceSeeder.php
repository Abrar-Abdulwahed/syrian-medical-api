<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title_en' => 'General Check-up',
                'title_ar' => 'الفحص العام',
                'category_id' => 1,
                'thumbnail' => fake()->imageUrl()
            ],
            [
                'title_en' => 'Medical Consultation',
                'title_ar' => 'الاستشارة الطبية',
                'category_id' => 1,
                'thumbnail' => fake()->imageUrl()
            ],
            [
                'title_en' => 'Prescription Dispensing',
                'title_ar' => 'توزيع الروشتة',
                'category_id' => 2,
                'thumbnail' => fake()->imageUrl()
            ],
            [
                'title_en' => 'Over-the-Counter Medication Dispensing',
                'title_ar' => 'صرف الأدوية بدون وصفة',
                'category_id' => 2,
                'thumbnail' => fake()->imageUrl()
            ],
            [
                'title_en' => 'Blood Test',
                'title_ar' => 'فحص الدم',
                'category_id' => 3,
                'thumbnail' => fake()->imageUrl()
            ],
            [
                'title_en' => 'Urine Analysis',
                'title_ar' => 'تحليل البول',
                'category_id' => 3,
                'thumbnail' => fake()->imageUrl()
            ],
            [
                'title_en' => 'Cardiology Check-up',
                'title_ar' => 'الفحص القلبي',
                'category_id' => 4,
                'thumbnail' => fake()->imageUrl()
            ],
            [
                'title_en' => 'Pediatric Care',
                'title_ar' => 'رعاية الأطفال',
                'category_id' => 4,
                'thumbnail' => fake()->imageUrl()
            ]
        ];

        foreach ($services as $item) {
            Service::create($item);
        }
    }
}
