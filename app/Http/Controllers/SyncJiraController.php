<?php

namespace App\Http\Controllers;

use App\Issues\InteractsWithIssueModel;
use App\Jobs\SyncJiraIssuesForProject;
use App\Projects\InteractsWithProjectModel;
use App\Services\InteractsWithJira;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        ])->validateWithBag('syncJiraProject');

        try {
            $jiraKey     = $request->post('jira_key');
            $jiraProject = $this->getJiraProject($jiraKey);
            $project     = $this->findOrCreateProject(
                $jiraProject->name,
                $jiraProject->key
            );

            if ($project) {
                (new SyncJiraIssuesForProject($project))->handle();
            }
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with([
                'syncJiraProject' => __('Could not sync project at this moment.')
            ]);
        }

        return back(303);
    }
}
