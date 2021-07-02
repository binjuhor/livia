<?php

namespace App\Jobs;

use App\Invoices\InteractsWithInvoiceModel;
use App\Invoices\InvoiceStatus;
use App\Invoices\InvoiceUtils;
use App\Issues\InteractsWithIssueModel;
use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\Issue;
use App\Models\Project;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CreateInvoice implements ShouldQueue
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
                if ($invoice) {
                    $invoice->lineItems()->upsert(
                        $this->makeItemLines($this->project)
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
                }
            }
        );
    }
}
