<?php

namespace App\Issues;

use Illuminate\Support\Collection;
use App\Models\Issue;

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
}