<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class OwnCharacter extends Model
{
    use HasFactory;

    protected $table = 'own_characters';
    protected $primaryKey = 'name';

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'character_name'
    ];

    public function GameCharacter() {
        return $this->belongsTo('App\Models\GameCharacter', 'character_name', 'en_name');
    }

    public function scopeBuildOwnCharacter($query, array $array) {
        $username = Arr::get($array, 'username');
        $character_name = Arr::get($array, 'character_name');

        return $query->firstOrCreate([
            'name' => $username,
            'character_name' => $character_name
        ]);
    }

    public function scopeBuildDefaultOwnCharacter($query, $username) {
        return $query->firstOrCreate([
            'name' => $username,
            'character_name' => 'Minato Aqua'
        ]);
    }

    public function scopeOwnedCharacter($query, array $array) {
        $self_name = Auth::user()->name;
        $username = Arr::get($array, 'username', $self_name);
        $character_name = Arr::get($array, 'character_name');

        $query->where('name', $username)->where('character_name', $character_name);
    }

    public function scopeOwnCharacterList($query) {
        $self_name = Auth::user()->name;

        $query->orderBy('created_at')->with(['GameCharacter' => function ($query) {
            $query->select('tc_name', 'en_name', 'img_file_name', 'vitality', 'energy', 'resistance', 'charm', 'introduction');
        }])->where('name', $self_name)->select('character_name');
    }
}
