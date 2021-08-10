<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'name';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
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

    public function scopeEmailUnverified($query, $email) {
        $email = $query->where('email', $email);

        if ($email->count() > 0) {
            $e_user = $email->first();

            if ($e_user->hasVerifiedEmail())
                return false;

            if ($e_user->name !== Auth::user()->name)
                $e_user->forceFill(['email' => null])->save();
        }

        return true;
    }

    public function scopeEmailVerifyStatus($query, $email) {
        $email = $query->where('email', $email);

        if ($email->count() > 0) {
            return $email->first()->hasVerifiedEmail();
        }

        return false;
    }


    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Send the email verification notification.
     *
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
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
