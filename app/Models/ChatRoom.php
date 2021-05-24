<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_room';

    protected $fillable = [
        'name',
        'message'
    ];

    public function scopeChat_info($query) {
        $query->join('game_info', 'game_info.name', '=', 'chat_room.name')
            ->select('chat_room.name', 'nickname', 'message', 'chat_room.created_at')
            ->orderby('chat_room.created_at', 'ASC');
    }

    public function GameInfo() {
        return $this->belongsTo('App\Models\GameInfo', 'name', 'name');
    }
}
