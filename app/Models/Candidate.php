<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'whatsapp',
        'resume_path',
        'source',
        'status',
        'notes',
    ];
}
