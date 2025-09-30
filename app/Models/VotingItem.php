<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VotingItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agm_id',
        'title',
        'description',
        'type',
        'options',
        'status',
        'completed_at',
        'vote_option'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the AGM that this voting item belongs to.
     */
    public function agm(): BelongsTo
    {
        return $this->belongsTo(Agm::class);
    }

    /**
     * Get the votes for the voting item.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Check if the voting item is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the total votes cast for the voting item.
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->votes()->sum('votes_cast');
    }

    /**
     * Get the breakdown of votes by option.
     */
    public function getVoteBreakdownAttribute(): array
    {
        return $this->votes()
            ->groupBy('vote_option')
            ->selectRaw('vote_option, sum(votes_cast) as total_votes')
            ->pluck('total_votes', 'vote_option')
            ->all();
    }

    /**
     * Get the number of shareholders who have voted.
     */
    public function getVoterCountAttribute(): int
    {
        return $this->votes()->distinct('shareholder_id')->count();
    }
}
