<?php

namespace App\Issues;

use Illuminate\Support\Collection;
use App\Models\Issue;

trait InteractsWithIssueModel
{
    public function updateOrCreateIssues(Collection $issuesData): int
    {
        return Issue::query()->upsert(
            $issuesData->all(),
            ['jira_key'],
            ['summary', 'story_point', 'type', 'status', 'project_id']
        );
    }
}