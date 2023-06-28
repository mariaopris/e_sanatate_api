<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'no_new_user',
        'no_new_diagnostic_h',
        'no_new_diagnostic_l',
    ];
}
