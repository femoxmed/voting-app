<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'shares_owned',
        'shareholder_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function hasVotedOn(VotingItem $item): bool
    {
        return $this->votes()->where('voting_item_id', $item->id)->exists();
    }
}