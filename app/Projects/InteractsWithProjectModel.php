<?php


namespace App\Projects;


use App\Models\Project;

trait InteractsWithProjectModel
{
    public function createProject(string $name, string $key)
    {
        return Project::factory()->create([
            'name'     => $name,
            'jira_key' => $key
        ]);
    }

    public function getProject($projectId)
    {
        return Project::query()->findOrFail($projectId);
    }
}