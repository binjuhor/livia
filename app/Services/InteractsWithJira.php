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

    private function searchJiraIssuesThisWeekByProject(string $projectKey): IssueSearchResult
    {
        return $this->searchJiraIssues(
            sprintf(
                'project = "%s" AND updatedDate >= startOfWeek()',
                $projectKey
            )
        );
    }

    private function searchJiraIssuesDoneThisWeekByProject(string $projectKey): IssueSearchResult
    {
        return $this->searchJiraIssues(
            sprintf(
                'project = "%s" AND updatedDate >= startOfWeek() AND status = DONE',
                $projectKey
            )
        );
    }

    private function searchJiraIssuesThisMonthByProject(string $projectKey): IssueSearchResult
    {
        return $this->searchJiraIssues(
            sprintf(
            'project = "%s" AND updatedDate >= startOfMonth()',
            $projectKey
            )
        );
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function searchJiraIssues(string $jql): IssueSearchResult
    {
        return (new IssueService)->search(
            $jql,
            0,
            9999,
            [
                '*all'
            ]
        );
    }
}