<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name_en' => 'visa',
                'name_ar' => 'فيزا',
                'logo' => 'https://www.google.com/url?sa=i&url=https%3A%2F%2Flogowik.com%2Fvisa-card-vector-logo-1248.html&psig=AOvVaw2q1mVxDftg85LgmiejZM7y&ust=1705922809467000&source=images&cd=vfe&ved=0CBIQjRxqFwoTCKCHvO-v7oMDFQAAAAAdAAAAABAE',
            ],
            [
                'name_en' => 'mastercard',
                'name_ar' => 'ماستر كارد',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/2560px-Mastercard-logo.svg.png'
            ]
        ];
        foreach ($methods as $key => $item) {
            DB::table('payment_methods')->insert([
                'name_en' => $item['name_en'],
                'name_ar' => $item['name_ar'],
                'logo' => $item['logo'],
                'created_at' => now(),
            ]);
        }
    }
}
