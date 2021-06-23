<?php


namespace App\Services;

use JiraRestApi\Issue\IssueSearchResult;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Project\Project;
use JiraRestApi\Project\ProjectService;

trait InteractsWithJira
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function getJiraProject(string $key): Project
    {
        return (new ProjectService)->get($key);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function getJiraIssuesByProject(string $projectKey): IssueSearchResult
    {
        return (new IssueService)->search(
            sprintf(
            'project = "%s" AND updatedDate >= startOfMonth()',
            $projectKey
            ),
            0,
            9999,
            ['summary', 'key']
        );
    }
}