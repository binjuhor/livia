<?php

namespace Tests;

use App\Issues\IssueStatus;
use App\Issues\IssueType;
use App\Models\Project;
use App\Projects\InteractsWithProjectModel;

trait InteractsWithTestProject
{
    use InteractsWithProjectModel;

    public function getTestProject()
    {
        return tap(
            $this->createProject('Test', 'TEST'),
            function (Project $project) {
                $project->issues()->createMany([
                    [
                        'summary'     => 'Issue summary 1',
                        'jira_key'    => 'TEST-1',
                        'story_point' => 1,
                        'status'      => IssueStatus::Done,
                        'type'        => IssueType::Task
                    ],
                    [
                        'summary'     => 'Issue summary 2',
                        'jira_key'    => 'TEST-2',
                        'story_point' => 2,
                        'status'      => IssueStatus::Done,
                        'type'        => IssueType::Bug
                    ],
                    [
                        'summary'     => 'Issue summary 3',
                        'jira_key'    => 'TEST-3',
                        'story_point' => 4,
                        'status'      => IssueStatus::ToDo,
                        'type'        => IssueType::Task
                    ],
                ]);
            }
        );
    }
}