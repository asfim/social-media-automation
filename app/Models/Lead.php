<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'platform',
        'profile_id',
        'phone',
        'email',
        'interested_service',
        'lead_score',
        'lead_status',
        'source',
        'notes',
        'last_contact'
    ];

    public function activities()
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function leadNotes()
    {
        return $this->hasMany(LeadNote::class);
    }
}
