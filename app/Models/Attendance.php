<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendance';

    protected $fillable = [
        'event_participant_id',
        'check_in_time',
        'check_in_method',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class, 'event_participant_id');
    }
}
