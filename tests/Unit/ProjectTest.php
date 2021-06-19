<?php

namespace Tests\Unit;

use App\Models\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
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
