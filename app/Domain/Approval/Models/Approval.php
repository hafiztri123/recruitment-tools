<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'candidate_id',
        'approver_id',
        'status',
        'comments',
        'approved_at'
    ];
}
