<?php

namespace App\Http\Controllers;

use App\Invoices\InteractsWithInvoiceModel;
use App\Jobs\UpsertInvoice;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Http\RedirectResponse;

class CreateInvoiceController extends Controller
{
    use InteractsWithProjectModel,
        InteractsWithInvoiceModel;

    public function __invoke(int $projectId): RedirectResponse
    {
        try {
            $project = $this->findProjectOrFail($projectId);
            (new UpsertInvoice($project))->handle();
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with([
                'createInvoice' => __('Could not create invoice at this moment.')
            ]);
        }

        return back(303);
    }
}
