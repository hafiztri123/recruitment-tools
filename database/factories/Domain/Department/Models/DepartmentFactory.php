<?php

namespace Database\Factories\Domain\Department\Models;

use App\Domain\Department\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Department::class;
    
    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}
