<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'doctor_id' => 1,
            'office_id' => 1,
            'start_time' => $this->faker->time('H:i', '23:59'),
            'end_time' => $this->faker->time('H:i', '23:59'),
            'week_day' => $this->faker->numberBetween(1, 7),
        ];
    }


    public function doctor($model)
    {
        return $this->state(function ($attrs) use ($model) {
            return [
                'doctor_id' => $model->id
            ];
        });
    }


    public function office($model)
    {
        return $this->state(function ($attrs) use ($model) {
            return [
                'office_id' => $model->id
            ];
        });
    }
}
