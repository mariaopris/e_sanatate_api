<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'recording_id',
        'disease_id',
        'type',
        'symptoms',
        'result',
        'result_short',
        'weighted_means_result',
        'predictions'
    ];
}
