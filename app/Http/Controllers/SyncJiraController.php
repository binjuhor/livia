<?php

namespace App\Http\Controllers;

use App\Issues\InteractsWithIssueModel;
use App\Issues\IssueStatus;
use App\Projects\InteractsWithProjectModel;
use App\Services\InteractsWithJira;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JiraRestApi\Issue\IssueV3;
use App\Models\Project;
use App\Issues\IssueType;

class SyncJiraController extends Controller
{
    use InteractsWithJira,
        InteractsWithProjectModel,
        InteractsWithIssueModel;

    /** @noinspection PhpUnhandledExceptionInspection */
    public function __invoke(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'jira_key' => [
                'required',
                'string',
                'max:255'
            ]
        ])->validateWithBag('addJiraProject');

        try {
            $jiraKey     = $request->post('jira_key');
            $jiraProject = $this->getJiraProject($jiraKey);
            $project     = $this->findOrCreate(
                $jiraProject->name,
                $jiraProject->key
            );

            if ($project) {
                $issuesData = collect(
                    $this->searchJiraIssuesThisWeekByProject($jiraKey)
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
                        'project_id'  => $project->id
                    ];
                });

                $this->updateOrCreateIssues($issuesData);
            }
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withMessage([
                'message' => __('Could not sync project at this moment.')
            ]);
        }

        return back(303);
    }
}
