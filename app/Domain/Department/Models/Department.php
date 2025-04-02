<?php

namespace App\Domain\Department\Models;

use Database\Factories\Domain\Department\Models\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return DepartmentFactory::new();
    }

    protected $fillable = [
        'name'
    ];

}
