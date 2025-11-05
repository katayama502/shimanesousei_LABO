<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'company_org_id',
        'tier_id',
        'amount',
        'message',
        'status',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function company()
    {
        return $this->belongsTo(Organization::class, 'company_org_id');
    }

    public function tier()
    {
        return $this->belongsTo(SponsorshipTier::class, 'tier_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
