<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class IssueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Issue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        return [
            'summary'    => $faker->name(),
            'jira_key'   => sprintf(
                '%s-%d',
                Str::upper($faker->word),
                $faker->numberBetween(1, 1000)
            ),
            'project_id' => Project::factory()
        ];
    }
}
