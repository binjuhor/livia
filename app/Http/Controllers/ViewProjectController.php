<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Projects\InteractsWithProjectModel;
use Inertia\Inertia;
use Inertia\Response;

class ViewProjectController extends Controller
{
    use InteractsWithProjectModel;

    public function __invoke(int $projectId): Response
    {
        return Inertia::render('Projects/View', [
            'project' => $this->getProject($projectId)
        ]);
    }
}
