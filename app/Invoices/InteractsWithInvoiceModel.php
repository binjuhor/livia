<?php


namespace App\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

    public function makeLineItems(Project $project): Collection
    {
        return collect(
            $this->findIssuesDoneThisWeek($project)
                 ->map(function (Issue $issue) {
                     return InvoiceLineItem::factory()->make([
                         'description' => sprintf(
                             '(%s) %s',
                             $issue->jira_key,
                             $issue->summary
                         ),
                         'invoice_id'  => null,
                         'xero_id'     => null,
                         'quantity'    => $issue->story_point,
                         'unit_amount' => Str::contains($issue->jira_key, 'TA') ? 15 : config('livia.rate', 20),
                         'issue_id'    => $issue->id
                     ]);
                 })
        );
    }

    private function createInvoiceFromProject(Project $project): ?Invoice
    {
        $reference   = InvoiceUtils::getWeeklyRef($project);
        $invoiceData = [
            'reference'  => $reference,
            'total'      => 0,
            'status'     => InvoiceStatus::Draft,
            'project_id' => $project->id
        ];

        Invoice::query()->upsert(
            $invoiceData,
            ['reference'],
            ['status']
        );

        return $this->findInvoiceByProject($project);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function findInvoice(int $id): ?Invoice
    {
        return Invoice::query()->find($id);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function findLineItemByDescription(string $description): ?InvoiceLineItem
    {
        $jiraKey = InvoiceUtils::getJiraKey($description);

        return InvoiceLineItem::query()
                              ->where('description', 'LIKE', "%{$jiraKey}%")
                              ->first();
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function findRecentInvoiceByAmount(int $amount): ?Invoice
    {
        return Invoice::query()
            ->where('total', $amount)
            ->where('created_at', '>=', now()->subDays(7))
            ->first();
    }
}
