<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'group_id'
    ];

    public function matchHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MatchHistory::class, 'match_id', 'match_id');
    }
}
