<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'voting_item_id',
        'shareholder_id',
        'vote_option',
        'votes_cast',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    public function votingItem()
    {
        return $this->belongsTo(VotingItem::class);
    }

    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }
}