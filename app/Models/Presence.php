<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presences';

    protected $fillable = [
        'worker_id',
        'date',
        'presence_schedule_id',
        'first_check_in',
        'first_check_in_type',
        'second_check_in',
        'second_check_in_type',
        'check_out',
        'check_out_type',
    ];

    public function schedule()
    {
        return $this->belongsTo(PresenceSchedule::class, 'presence_schedule_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
