<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Projects\InteractsWithProjectModel;
use Inertia\Inertia;
use Inertia\Response;

class ViewProjectController extends Controller
{
    use InteractsWithProjectModel;

    public function __invoke(int $projectId): Response
    {
        return Inertia::render('Projects/ProjectView', [
            'project' => $this->findProjectOrFail($projectId)
                              ->load('invoices'),

            'issues' => Issue::query()
                             ->where('project_id', $projectId)
                             ->paginate(10)
        ]);
    }
}
