<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_room';

    protected $fillable = [
        'id',
        'name',
        'message'
    ];

    public function GameInfo() {
        return $this->belongsTo('App\Models\GameInfo', 'name', 'name');
    }
}
