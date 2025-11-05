<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'slug',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, $this->type === 'sport' ? 'sport_category_id' : 'culture_category_id');
    }
}
