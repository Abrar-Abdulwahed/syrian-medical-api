<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'attach_detach_permission',
                'description_en' => 'Allowing attach or detach permissions to/from supervisor',
                'description_ar' => 'السماح بإرفاق أو فصل الأذونات من/إلى المشرف'
            ],
            [
                'name' => 'accept_registration_request',
                'description_en' => 'Allowing acceptance of registration requests',
                'description_ar' => 'السماح بقبول طلبات التسجيل'
            ],
            [
                'name' => 'add_supervisor',
                'description_en' => 'Allow adding new supervisors',
                'description_ar' => 'السماح بإضافة مشرفين جدد'
            ],
            [
                'name' => 'modify_user_data',
                'description_en' => 'Allow modification of user data',
                'description_ar' => 'السماح بتعديل بيانات المستخدم'
            ],
            [
                'name' => 'block_user',
                'description_en' => 'Allow blocking users',
                'description_ar' => 'السماح بحذف المستخدمين'
            ],
        ];

        foreach ($permissions as $key => $value) {
            Permission::create($value);
        }
    }
}
