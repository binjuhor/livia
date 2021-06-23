<?php

namespace App\Http\Controllers;

use App\Jobs\CreateInvoice;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Http\RedirectResponse;

class CreateInvoiceController extends Controller
{
    use InteractsWithProjectModel;

    public function __invoke(int $projectId): RedirectResponse
    {
        (new CreateInvoice(
            $this->getProject($projectId)
        ))->handle();

        return back(303);
    }
}
