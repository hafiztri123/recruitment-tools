<?php

namespace App\Domain\Position\Models;

use App\Domain\Department\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    //

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'department_id'
    ];


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
