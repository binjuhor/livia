<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Atlassian\JiraRest\Requests\Project\ProjectRequest;

class ImportJiraProjectController extends Controller
{
    public function __invoke(Request $request)
    {
        Validator::make($request->all(), [
            'jira_code' => ['required', 'string', 'max:255']
        ])->validateWithBag('addJiraProject');

        $projectRequest = new ProjectRequest;

        dd($projectRequest->all());

        return [];
    }
}
