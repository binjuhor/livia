<?php

namespace App\Http\Controllers;

use App\Projects\InteractsWithProjectModel;
use App\Services\InteractsWithJira;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JiraRestApi\Project\Project;

class ImportJiraProjectController extends Controller
{
    use InteractsWithJira;
    use InteractsWithProjectModel;

    public function __invoke(Request $request)
    {
        Validator::make($request->all(), [
            'jira_code' => ['required', 'string', 'max:255']
        ])->validateWithBag('addJiraProject');

        tap(
            $this->getJiraProject($request->post('jira_code')),
            function(Project $project){
                $this->createProject(
                    $project->name,
                    $project->key
                );
            }
        );

        return back(303);
    }
}
