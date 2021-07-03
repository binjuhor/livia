<?php

namespace Database\Factories;

use App\Models\InvoiceLineItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceLineItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceLineItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'description' => $faker->name(),
            'xero_id'     => $faker->uuid,
            'quantity'    => $faker->numberBetween(1, 1000),
            'unit_amount' => $faker->numberBetween(1, 1000)
        ];
    }
}
