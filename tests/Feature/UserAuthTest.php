<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testPatientCanRegister()
    {
        $response = $this->post('/api/register/patient', [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => 'abrar',
            'password' => '123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function testServiceProviderCanRegister()
    {
        $file = UploadedFile::fake()->create('document.pdf');

        $response = $this->post('/api/register/service-provider', [
            'firstname' => 'abrar',
            'lastname' => 'alkhorasani',
            'email' => 'abrari@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'bank_name' => 'Example Bank',
            'iban_number' => '23343984398',
            'swift_code' => 'CTBAAU2S',
            'evidence' => $file,
        ]);
        $response->assertStatus(200);
    }
}
