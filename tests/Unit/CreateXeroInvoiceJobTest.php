<?php

namespace Tests\Unit;

use App\Invoices\InteractsWithInvoiceModel;
use App\Invoices\InvoiceStatus;
use App\Jobs\CreateXeroInvoice;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CreateXeroInvoiceJobTest extends TestCase
{
    use InteractsWithInvoiceModel,
        DatabaseTransactions;

    public function test_it_can_create_xero_invoice()
    {
        $invoice = $this->createInvoice(
            'TEST-252021',
            460,
            InvoiceStatus::Draft,
            null
        );

        (new CreateXeroInvoice($invoice))->handle();

        $invoice->refresh();
        $this->assertNotNull($invoice->xero_id);
    }
}
