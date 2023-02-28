<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimerSegment extends Model
{
    use HasFactory;
    protected $table = 'timer_segments';
    protected $fillable = [
        'timer_id',
        'segment_name',
        'duration',
        'end_sound',
    ];
}
