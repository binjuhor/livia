<?php

namespace App\Http\Controllers;

use App\Projects\InteractsWithProjectModel;
use App\Services\InteractsWithJira;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JiraRestApi\Issue\IssueSearchResult;
use JiraRestApi\Issue\IssueV3;
use App\Models\Project;

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
                'max:255',
                'unique:projects'
            ]
        ])->validateWithBag('addJiraProject');

        try {
            $jiraKey     = $request->post('jira_key');
            $jiraProject = $this->getJiraProject($jiraKey);

            tap(
                $this->createProject(
                    $jiraProject->name,
                    $jiraProject->key
                ),
                function (Project $project) use ($jiraKey) {

                    $issuesData = collect(
                        $this->getJiraIssuesByProject($jiraKey)->getIssues()
                    )->map(function (IssueV3 $jiraIssue) {
                        return [
                            'jira_key' => $jiraIssue->key,
                            'summary'  => $jiraIssue->fields->summary
                        ];
                    });

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
