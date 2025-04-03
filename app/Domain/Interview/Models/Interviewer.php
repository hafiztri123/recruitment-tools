<?php


namespace App\Domain\Interview\Models;

use Illuminate\Database\Eloquent\Model;

class Interviewer extends Model
{
    protected $fillable = [
        'interview_id',
        'user_id',
        'feedback_submitted',
        'feedback',
        'rating'
    ];
}
