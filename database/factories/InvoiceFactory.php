<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        return [
            'reference'  => $faker->name(),
            'xero_id'    => $faker->uuid,
            'project_id' => Project::factory(),
            'total'      => $faker->numberBetween(100, 1000),
            'status'     => $faker->numberBetween(0, 1), // Use Enum later
        ];
    }
}
