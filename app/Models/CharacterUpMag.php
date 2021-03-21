<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterUpMag extends Model
{
    use HasFactory;

    protected $table = 'characters_up_mag';
    protected $primaryKey = 'character_name';

    public $keyType = 'string';
    public $incrementing = false;
}
