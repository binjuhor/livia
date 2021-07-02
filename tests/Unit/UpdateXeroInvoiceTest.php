<?php

namespace Tests\Unit;

use App\Invoices\InteractsWithInvoiceModel;
use App\Jobs\CreateXeroInvoice;
use App\Jobs\UpdateXeroInvoice;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\InteractsWithTestProject;
use Tests\TestCase;

class UpdateXeroInvoiceTest extends TestCase
{
    use InteractsWithInvoiceModel,
        InteractsWithTestProject,
        DatabaseTransactions;

    protected Invoice $invoice;

    public function setUp(): void
    {
        parent::setUp();

        $this->invoice = $this->findInvoiceByProject(
            $this->findProject(1)
        );
    }

    public function test_it_can_update_xero_invoice()
    {
        // @TODO: Mocking Xero API
        $invoice = $this->invoice;

        $result = (new UpdateXeroInvoice($invoice))->handle();
        dd($result);
    }
}
