<?php

namespace App\Http\Controllers;

use App\Invoices\InteractsWithInvoiceModel;
use App\Jobs\CreateInvoice;
use App\Jobs\CreateXeroInvoice;
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
            $invoice = $this->findInvoiceByProject($project)
                ?: (new CreateInvoice($project))->handle();;

            if ($invoice->hasXeroId()) {
                // Update invoice
            }else{
                (new CreateXeroInvoice($invoice))->handle();
            }
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with([
                'createInvoice' => __('Could not create invoice at this moment.')
            ]);
        }

        return back(303);
    }
}
