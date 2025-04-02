<?php

namespace App\Domain\RecruitmentBatch\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentBatch extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'description'
    ];


}
