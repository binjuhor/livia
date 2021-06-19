<?php


namespace App\Projects;


use App\Models\Project;

trait InteractsWithProjectModel
{
    public function createProject(string $name, string $jiraCode)
    {
        return Project::factory()->create([
            'name'      => $name,
            'jira_code' => $jiraCode
        ]);
    }

    public function getProject($projectId)
    {
        return Project::findOrFail($projectId);
    }
}