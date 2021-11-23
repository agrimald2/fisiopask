<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sex = $this->faker->boolean();
        return [
            'name' => $this->faker->name($sex ? 'male' : 'female'),
            'lastname1' => $this->faker->lastName(),
            'lastname2' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'dni' => $this->faker->ean8(),
            'phone' => $this->faker->phoneNumber(),
            'birth_date' => now()->parse($this->faker->dateTimeBetween('-60 years', '-15 years'))->toDateString(),
            'sex' => $sex ? 'M' : 'F',
        ];
    }

    public function withoutPhone()
    {
        return $this->state(function (array $attributes) {
            return [
                'phone' => null,
            ];
        });
    }
}
