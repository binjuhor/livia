<?php


namespace App\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Support\Collection;

trait InteractsWithInvoiceModel
{
    public function createInvoice(
        string $reference,
        string $total,
        int $status
    ) {
        return Invoice::factory()->create([
            'reference' => $reference,
            'total'     => $total,
            'status'    => $status,
            'xero_id'   => null
        ]);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function findInvoiceByProject(Project $project): ?Invoice
    {
        return Invoice::query()
                      ->where(
                          'reference',
                          InvoiceUtils::getWeeklyRef($project)
                      )->first();
    }

    public function makeItemLines(Project $project): Collection
    {
        return collect(
            $this->findIssuesDoneThisWeek($project)
                 ->map(function (Issue $issue) {
                     return InvoiceLineItem::factory()->make([
                         'description' => $issue->summary,
                         'invoice_id'  => null,
                         'xero_id'     => null,
                         'quantity'    => $issue->story_point,
                         'unit_amount' => config('livia.rate', 20),
                         'issue_id'    => $issue->id
                     ]);
                 })
        );
    }

    private function createInvoiceFromProject(Project $project): ?Invoice
    {
        $itemLines   = $this->makeItemLines($project);
        $reference   = InvoiceUtils::getWeeklyRef($project);
        $invoiceData = [
            'reference'  => $reference,
            'total'      => $itemLines->sum('total'),
            'status'     => InvoiceStatus::Draft,
            'project_id' => $project->id
        ];

        Invoice::query()->upsert(
            $invoiceData,
            ['reference'],
            ['total', 'status']
        );

        return $this->findInvoiceByProject($project);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function findInvoice(int $id): ?Invoice
    {
        return Invoice::query()->find($id);
    }
}