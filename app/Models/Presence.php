<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'date',
        'first_check_in',
        'second_check_in',
        'check_out',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
