<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function coach()
    {
        return $this->hasOne(Coach::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function scopeFilter($query, array $filters) 
    {

        if (isset($filters['roleFilter']) && $filters['roleFilter'] !== 'all') {
            $query->whereHas('roles', function ($roleQuery) use ($filters) {
                $roleQuery->where('name', $filters['roleFilter']);
            });
        }

        if ($filters['search'] ?? false) {
            $search = $filters['search']; 
            $query->where(function ($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }
}
