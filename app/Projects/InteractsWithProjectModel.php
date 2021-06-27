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
            return $this->findProject($input);
        }

        if (is_string($input)) {
            return $this->findProjectByKey($input);
        }

        abort(400, __('Bad input to find project.'));
    }

    /**
     * @param int $projectId
     * @return Project|null
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function findProject(int $projectId): ?Project
    {
        return Project::query()
                      ->find($projectId);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function findProjectOrFail(int $projectId): ?Project
    {
        return Project::query()
                      ->findOrFail($projectId);
    }

    public function findProjectByKey(string $key)
    {
        return Project::query()
                      ->where('jira_key', $key)
                      ->first();
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function findOrCreateProject(string $name, string $key): Project
    {
        return Project::query()->where('jira_key', $key)->first()
            ?: $this->createProject($name, $key);
    }

    private function findProjectDoneIssues(Project $project): EloquentCollection
    {
        return $project->issues()
                       ->where('status', IssueStatus::Done)
                       ->get();
    }
}