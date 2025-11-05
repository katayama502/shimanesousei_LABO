<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorshipTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'amount',
        'description',
        'limit_qty',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class, 'tier_id');
    }
}
