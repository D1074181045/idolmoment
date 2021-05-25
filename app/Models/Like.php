<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Like extends Model
{
    use HasFactory;

    protected $table = 'like';

    protected $fillable = [
        'id',
        'from_name',
        'to_name',
        'type'
    ];

    public function updateType($type) {
        $this->type = $type;
        $this->save();
    }

    public function scopeCreateLike($query, $opposite_name, $type) {
        return $query->firstOrCreate([
            'from_name' => Auth::user()->name,
            'to_name' => $opposite_name,
        ], [
            'type' => $type
        ]);
    }

    public function scopeDeleteLike($query, $opposite_name) {
        $like = $query->where([
            'from_name' => Auth::user()->name,
            'to_name' => $opposite_name,
        ])->first();

        if ($like)
            $like->delete();
    }

    public function scopeLikeNum($query, $name) {
        $like = $query
            ->select(DB::raw('count(type)'))->where('type', 'like')
            ->groupBy('to_name')->having('to_name', $name)
            ->first();

        if ($like)
            return $like->count;
        else
            return 0;
    }

    public function scopeDisLikeNum($query, $name) {
        $like = $query
            ->select(DB::raw('count(type)'))->where('type', 'dislike')
            ->groupBy('to_name')->having('to_name', $name)
            ->first();

        if ($like)
            return $like->count;
        else
            return 0;
    }

    public function scopeSelectedType($query, $from_name, $to_name) {
        $like = $query
            ->where('from_name', $from_name)
            ->where('to_name', $to_name)
            ->first();

        if ($like)
            return $like->type;
        else
            return null;
    }
}
