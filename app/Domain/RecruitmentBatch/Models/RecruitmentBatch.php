<?php

namespace App\Domain\RecruitmentBatch\Models;

use App\Domain\Position\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentBatch extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'description'
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }


}
