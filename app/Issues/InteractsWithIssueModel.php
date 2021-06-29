<?php

namespace App\Issues;

use Illuminate\Support\Collection;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

trait InteractsWithIssueModel
{
    public function upsertIssues(Collection $issuesData): int
    {
        return Issue::query()->upsert(
            $issuesData->all(),
            ['jira_key'],
            [
                'summary',
                'story_point',
                'type',
                'status',
                'project_id',
                'created_at',
                'updated_at'
            ]
        );
    }

    public function findIssuesDoneThisWeek(Project $project): EloquentCollection
    {
        return Issue::query()
                    ->where('project_id', $project->id)
                    ->where('status', IssueStatus::Done)
                    ->where('updated_at', '>=', now()->startOfWeek())
                    ->get();
    }
}