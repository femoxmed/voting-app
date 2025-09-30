<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agm extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'start_date',
        'status',
        'reminder_sent',
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function votingItems()
    {
        return $this->hasMany(VotingItem::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
        //In a real application shareholders would only be able to vote during the meeting time
        //&& now()->greaterThanOrEqualTo($this->start_date);
    }
}
