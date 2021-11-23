<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Doctor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'birth_date' => now()->subYears($this->faker->numberBetween(20, 50))->toDateString(),
            'sex' => $this->faker->randomElement(['M', 'F']),
            'phone' => $this->faker->ean8(),
            'document_type' => 'DNI',
            'document_reference' => $this->faker->ean8()
        ];
    }
}
