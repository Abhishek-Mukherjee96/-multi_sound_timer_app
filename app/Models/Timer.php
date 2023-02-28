<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;
    protected $table = 'timers';
    protected $fillable = [
        'user_id',
        'timer_title',
        'timer_subhead',
        'start_sound',
    ];
}
