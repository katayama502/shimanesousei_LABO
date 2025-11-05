<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'slug',
        'summary',
        'description',
        'sport_category_id',
        'culture_category_id',
        'target_amount',
        'current_amount',
        'start_at',
        'end_at',
        'status',
        'prefecture',
        'city',
        'thumbnail_path',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class);
    }

    public function tiers(): HasMany
    {
        return $this->hasMany(SponsorshipTier::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->end_at) {
            return null;
        }

        $now = Carbon::now();
        if ($this->end_at->isPast()) {
            return 0;
        }

        return $now->diffInDays($this->end_at, false);
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed' || ($this->end_at && $this->end_at->isPast());
    }
}
