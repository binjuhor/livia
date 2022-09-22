<?php

namespace App\Jobs;

use App\Invoices\InteractsWithInvoiceModel;
use App\Issues\InteractsWithIssueModel;
use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\Project;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpsertInvoice implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithProjectModel,
        InteractsWithIssueModel,
        InteractsWithInvoiceModel;

    protected Project $project;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     */
    public function handle(): Invoice
    {
        return tap(
            $this->createInvoiceFromProject($this->project),
            function (?Invoice $invoice) {

                if (is_null($invoice)) {
                    return null;
                }

                $invoice->lineItems()->upsert(
                    // @TODO: Move this block to a function `makeLineItemsFromInvoice`
                    $this->makeLineItems($this->project)
                         ->map(function (InvoiceLineItem $lineItem) use ($invoice) {
                             $lineItem->invoice_id = $invoice->id;

                             return $lineItem;
                         })
                         ->toArray(),
                    ['issue_id'],
                    [
                        'description',
                        'quantity',
                        'unit_amount'
                    ]
                );

                $invoice->total = $invoice->lineItems->sum('total');
                $invoice->save();

                $invoice->refresh();

                (new CreatePdfInvoice($invoice))->handle();

//                if ($invoice->hasXeroId()) {
//                    (new UpdateXeroInvoice($invoice))->handle();
//                } else {
//                    (new CreateXeroInvoice($invoice))->handle();
//                }
            }
        );
    }
}
