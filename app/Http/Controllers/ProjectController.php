<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Projects', [
            'projects' => Project::all()
        ]);
    }
}
