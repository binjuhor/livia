<?php

namespace App\Http\Controllers;

use App\Jobs\CreateInvoice;
use App\Jobs\CreateXeroInvoice;
use App\Models\Invoice;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Http\RedirectResponse;

class CreateInvoiceController extends Controller
{
    use InteractsWithProjectModel;

    public function __invoke(int $projectId): RedirectResponse
    {
        //@TODO: Catching exception and return errors to the client.
        tap(
            (new CreateInvoice(
                $this->getProject($projectId)
            ))->handle(),
            function (Invoice $invoice) {
                (new CreateXeroInvoice($invoice))->handle();
            }
        );

        return back(303);
    }
}
