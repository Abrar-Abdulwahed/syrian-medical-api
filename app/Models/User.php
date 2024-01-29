<?php

namespace App\Models;

use App\Enums\UserType;
use App\Models\Product;
use App\Models\Service;
use App\Models\PatientProfile;
use App\Models\ProviderProfile;
use App\Models\ProviderService;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\PendingUpdateProfileRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'picture',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activated' => 'boolean'
    ];

    // Accessor
    protected function attachmentPath(): Attribute
    {
        return Attribute::make(
            get: fn () => '/profiles' . '/' . $this->id,
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    // Relations
    public function serviceProviderProfile()
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function profile()
    {
        if ($this->isPatient())
            return $this->patientProfile();
        else
            return $this->serviceProviderProfile();
    }

    public function pendingUpdateProfileRequest()
    {
        return $this->hasOne(PendingUpdateProfileRequest::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'provider_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'provider_service', 'provider_id', 'service_id')
            ->using(ProviderService::class)
            ->withPivot('id', 'price', 'description', 'discount')
            ->withTimestamps();
    }

    public function providerServices()
    {
        return $this->hasMany(ProviderService::class, 'provider_id');
    }

    // relationship for patient ONLY
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'patient_id');
    }

    public function isPatient()
    {
        return $this->type === UserType::PATIENT->value;
    }

    public function isServiceProvider()
    {
        return $this->type === UserType::SERVICE_PROVIDER->value;
    }

    // Events
    // protected $dispatchesEvents = [
    //     'created' => RegisterEvent::class,
    // ];
}
