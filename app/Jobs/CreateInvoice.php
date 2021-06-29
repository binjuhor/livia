<?php

namespace App\Jobs;

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
        InteractsWithIssueModel;

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
        $project   = $this->project;
        $itemLines = $this->createItemLinesFromProject($project);

        return tap(
            $project->invoices()->create([
                'reference' => InvoiceUtils::generateWeeklyInvoiceReference($project),
                'total'     => $itemLines->sum('total'),
                'status'    => InvoiceStatus::Draft,
                'xero_id'   => null
            ]),
            function (Invoice $invoice) use ($itemLines) {
                $invoice->lineItems()->saveMany(
                    $itemLines->all()
                );
            }
        );
    }

    public function createItemLinesFromProject(Project $project): Collection
    {
        return collect(
            $this->findIssuesDoneThisWeek($project)
                 ->map(function (Issue $issue) {
                     return InvoiceLineItem::factory()->make([
                         'description' => $issue->summary,
                         'invoice_id'  => null,
                         'xero_id'     => null,
                         'quantity'    => $issue->story_point,
                         'unit_amount' => config('livia.rate', 20)
                     ]);
                 })
        );
    }
}
