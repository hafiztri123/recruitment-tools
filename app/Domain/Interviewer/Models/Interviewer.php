<?php


namespace App\Domain\Interviewer\Models;

use App\Domain\Interview\Models\Interview;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interviewer extends Model
{
    protected $fillable = [
        'interview_id',
        'user_id',
        'feedback_submitted',
        'feedback',
        'rating'
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(
            related: Interview::class,
            foreignKey: 'interview_id',
            ownerKey: 'id',
        );
    }
}
