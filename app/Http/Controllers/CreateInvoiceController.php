<?php

namespace App\Http\Controllers;

use App\Jobs\CreateInvoice;
use App\Jobs\CreateXeroInvoice;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Http\RedirectResponse;

class CreateInvoiceController extends Controller
{
    use InteractsWithProjectModel;

    public function __invoke(int $projectId): RedirectResponse
    {
        try {
            $invoice = (new CreateInvoice(
                $this->findProjectOrFail($projectId)
            ))->handle();

            if ($invoice) {
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
