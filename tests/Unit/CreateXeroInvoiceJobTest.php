<?php

namespace Tests\Unit;

use App\Invoices\InteractsWithInvoiceModel;
use App\Jobs\CreateInvoice;
use App\Jobs\CreateXeroInvoice;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\InteractsWithTestProject;
use Tests\TestCase;

class CreateXeroInvoiceJobTest extends TestCase
{
    use InteractsWithInvoiceModel,
        InteractsWithTestProject,
        DatabaseTransactions;

    protected Invoice $invoice;

    public function setUp(): void
    {
        parent::setUp();

        $this->invoice = (new CreateInvoice(
            $this->getTestProject()
        ))->handle();
    }

    public function test_it_can_create_xero_invoice()
    {
        // @TODO: Mocking Xero API
        $invoice = $this->invoice;

        (new CreateXeroInvoice($invoice))->handle();

        $invoice->refresh();
        $this->assertNotNull($invoice->xero_id);
    }
}
