<?php


namespace App\Projects;


use App\Issues\IssueStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

trait InteractsWithProjectModel
{
    public function createProject(string $name, string $key)
    {
        return Project::factory()->create([
            'name'     => $name,
            'jira_key' => $key
        ]);
    }

    public function resolveProject($input)
    {
        if (is_numeric($input)) {
            return $this->getProject($input);
        }

        if (is_string($input)) {
            return $this->getProjectByKey($input);
        }

        abort(400, __('Bad input to find project.'));
    }

    /**
     * @param int $projectId
     * @return Project|null
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function getProject(int $projectId): ?Project
    {
        return Project::query()
                      ->find($projectId);
    }

    public function getProjectByKey(string $key)
    {
        return Project::query()
                      ->where('jira_key', $key)
                      ->first();
    }

    public function findOrCreate(string $name, string $key)
    {
        return Project::query()->where('jira_key', $key)->first()
            ?: $this->createProject($name, $key);
    }

    private function getProjectDoneIssues(Project $project): EloquentCollection
    {
        return $project->issues()
                       ->where('status', IssueStatus::Done)
                       ->get();
    }
}