<?php


namespace App\Invoices;

use App\Models\Invoice;
use App\Models\Project;

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
}