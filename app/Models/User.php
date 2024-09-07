<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'plataforma',
        'estatus',
        'role',
        'roles_id',
        'permisos',
        'token_recuperacion',
        'times_recuperacion',
        'rowquid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //AdminLTE
    public function adminlte_image(): string
    {
        //return 'https://picsum.photos/300/300';
        //return "https://ui-avatars.com/api/?name=".Auth::user()->name."&color=7F9CF5&background=EBF4FF";
        //return verImagen(auth()->user()->profile_photo_path, auth()->user()->name);
        return verImagen(Auth::user()->profile_photo_path, true);
    }

    public function adminlte_desc(): string
    {
        $user = Auth::user();
        $role = '';
        if (!empty(verRole($user->role, $user->roles_id))){
            $role = " [".verRole($user->role, $user->roles_id)."]";
        }
        return $user->email . $role;
    }

    public function adminlte_profile_url(): string
    {
        return 'dashboard/perfil';
    }

    public function scopeBuscar($query, $keyword)
    {
        return $query->where('name', 'LIKE', "%$keyword%")
            ->orWhere('email', 'LIKE', "%$keyword%")
            ->orWhere('id', 'LIKE', "%$keyword%")
            ;
    }

    public function chats(): HasMany
    {
        return $this->hasMany(ChatUser::class, 'users_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'users_id', 'id');
    }

    public function fcm(): HasMany
    {
        return $this->hasMany(Fcm::class, 'users_id', 'id');
    }

}
