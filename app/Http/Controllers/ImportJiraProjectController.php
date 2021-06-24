<?php

namespace App\Http\Controllers;

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

class ImportJiraProjectController extends Controller
{
    use InteractsWithJira;
    use InteractsWithProjectModel;

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
            $jiraKey               = $request->post('jira_key');
            $jiraProject           = $this->getJiraProject($jiraKey);
            $issuesMappingCallback = function (IssueV3 $jiraIssue) {
                return [
                    'jira_key'    => $jiraIssue->key,
                    'summary'     => $jiraIssue->fields->summary,
                    'story_point' => $jiraIssue->fields->customFields[ 'customfield_10016' ] ?? 0,
                    'type'        => IssueType::fromKey(
                        $jiraIssue->fields->issuetype->name
                    ),
                    'status'      => IssueStatus::fromKey(
                        Str::remove(' ', $jiraIssue->fields->status->name)
                    )
                ];
            };

            tap(
                $this->findOrCreate(
                    $jiraProject->name,
                    $jiraProject->key
                ),
                function (Project $project) use ($issuesMappingCallback, $jiraKey) {
                    $issuesData = collect(
                        //@TODO Use all issues callback later
                        $this->searchJiraIssuesThisWeekByProject($jiraKey)
                             ->getIssues()
                    )->map($issuesMappingCallback);

                    $project->issues()->createMany(
                        $issuesData->all()
                    );
                }
            );

        } catch (\Exception $exception) {
            report($exception);
            abort(500, __('Could not import project at this moment.'));
        }

        return back(303);
    }
}
