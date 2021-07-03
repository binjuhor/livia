<?php

namespace App\Jobs;

use App\Issues\InteractsWithIssueModel;
use App\Issues\IssueStatus;
use App\Issues\IssueType;
use App\Models\Project;
use App\Services\InteractsWithJira;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use JiraRestApi\Issue\IssueV3;

class SyncJiraIssuesForProject implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithJira,
        InteractsWithIssueModel;

    protected Project $project;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $project = $this->project;

        $issuesData = collect(
            $this->searchJiraIssuesThisWeekByProject($project->jira_key)
                 ->getIssues()
        )->map(function (IssueV3 $jiraIssue) use ($project) {
            return [
                'jira_key'    => $jiraIssue->key,
                'summary'     => $jiraIssue->fields->summary,
                'story_point' => $jiraIssue->fields->customFields[ 'customfield_10016' ] ?? 0,
                'type'        => IssueType::fromKey(
                    $jiraIssue->fields->issuetype->name
                ),
                'status'      => IssueStatus::fromKey(
                    Str::remove(' ', $jiraIssue->fields->status->name)
                ),
                'project_id'  => $project->id,
                'created_at' => $jiraIssue->fields->created,
                'updated_at' => $jiraIssue->fields->updated
            ];
        });

        $this->upsertIssues($issuesData);
    }
}
