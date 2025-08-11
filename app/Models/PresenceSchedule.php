<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceSchedule extends Model
{
    use HasFactory;

    protected $table = 'presences_schedules';

    protected $fillable = [
        'date',
        'first_check_in_start',
        'first_check_in_end',
        'second_check_in_start',
        'second_check_in_end',
        'check_out_start',
        'check_out_end',
    ];

    public function presences()
    {
        return $this->hasMany(Presence::class, 'presences_schedule_id');
    }
}
