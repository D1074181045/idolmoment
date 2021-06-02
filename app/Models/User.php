<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'users';
    protected $primaryKey = 'name';

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'password',
        'remember_token',
        'logged_at'
    ];

    /**
     * 遊戲資料
     *
     *
     */
    public function GameInfo()
    {
        return $this->hasOne('App\Models\GameInfo', 'name');
    }

    /**
     * 新增使用者
     *
     *
     * @param $query
     * @param array $userinfo
     * @return
     */
    public function scopeBuildUser($query, array $userinfo) {
        return $query->firstOrCreate([
            'name' => $userinfo['username'],
        ], [
            'password' => Hash::make($userinfo['password'])
        ]);
    }

    public function scopeAlreadyEmail($query, $email) {
        return $query->where('email', $email)->count() > 0;
    }

    public function scopeEmailVerified($query, $email) {
        $email = $query->where('email', $email);

        if ($email->count() > 0) {
            $e_user = $email->first();
            if ($e_user->hasVerifiedEmail())
                return true;
            $e_user->forceFill(['email' => null])->save();
        }

        return false;
    }

    public function scopeEmailVerifyStatus($query, $email) {
        $email = $query->where('email', $email);

        if ($email->count() > 0) {
            return $email->first()->hasVerifiedEmail();
        }

        return false;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
