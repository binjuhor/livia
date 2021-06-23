<?php


namespace App\Invoices;

use App\Models\Invoice;

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
}