<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CoolDown extends Model
{
    use HasFactory;

    protected $table = 'cool_down';
    protected $primaryKey = 'name';

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'signature',
        'chat'
    ];

    /**
     * 玩家遊戲資料
     *
     *
     */
    public function GameInfo() {
        return $this->belongsTo('App\Models\GameInfo', 'name', 'name');
    }

    /**
     * 玩家遊戲資料
     *
     * @param $query
     * @return false
     */
    public function scopeCurrentLoginUser($query) {
        $self_name = Auth::user()->name;

        if ($self_name)
            return $query->findOrFail($self_name);
        else
            return false;
    }

    public function update_signature($add_time) {
        $this->signature = Carbon::now()->addSeconds($add_time);
        $this->save();

        return $this->signature;
    }

    public function update_activity($add_time) {
        $this->activity = Carbon::now()->addSeconds($add_time);
        $this->save();

        return $this->activity;
    }

    public function update_chat($add_time) {
        $this->chat = Carbon::now()->addSeconds($add_time);
        $this->save();

        return $this->chat;
    }

    public function update_operating($add_time) {
        $this->operating = Carbon::now()->addSeconds($add_time);
        $this->save();

        return $this->operating;
    }

    public function update_cooperation($add_time) {
        $this->cooperation = Carbon::now()->addSeconds($add_time);
        $this->save();

        return $this->cooperation;
    }
}
