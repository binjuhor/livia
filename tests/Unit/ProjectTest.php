<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Project basic model test
     *
     * @return void
     */
    public function test_project_instance()
    {
        $project = Project::factory()->make();

        $this->assertInstanceOf(Project::class, $project);
        $this->assertIsString($project->name);
        $this->assertIsString($project->jira_code);
    }
}
