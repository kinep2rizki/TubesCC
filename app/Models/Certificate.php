<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_participant_id',
        'template_style',
        'file_url',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class, 'event_participant_id');
    }
}
