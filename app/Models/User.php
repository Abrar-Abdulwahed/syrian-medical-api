<?php

namespace App\Models;

use App\Enums\UserType;
use App\Events\RegisterEvent;
use App\Models\PatientProfile;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ServiceProviderProfile;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Models\PendingUpdateProfileRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
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
        'activated'=> 'boolean'
    ];

    // Accessor
    protected function attachmentPath(): Attribute
    {
        return Attribute::make(
            get: fn() => '/profiles'. '/' . $this->id,
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->firstname.' '.$this->lastname,
        );
    }

    // Relations
    public function serviceProviderProfile()
    {
        return $this->hasOne(ServiceProviderProfile::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function pendingUpdateProfileRequest()
    {
        return $this->hasOne(PendingUpdateProfileRequest::class);
    }

    //scopes
    public function isPatient(){
        return $this->type === UserType::PATIENT->value;
    }

    public function isServiceProvider(){
        return $this->type === UserType::SERVICE_PROVIDER->value;
    }

    // Events
    // protected $dispatchesEvents = [
    //     'created' => RegisterEvent::class,
    // ];
}
