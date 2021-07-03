<?php

namespace Tests\Unit;

use App\Jobs\UpsertInvoice;
use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\InteractsWithTestProject;
use Tests\TestCase;

class CreateInvoiceJobTest extends TestCase
{
    use InteractsWithTestProject,
        DatabaseTransactions;

    protected Project $project;

    public function setUp(): void
    {
        parent::setUp();
        $this->project = $this->getTestProject();
    }

    public function test_it_can_create_invoice_from_project()
    {
        $invoice = (new UpsertInvoice($this->project))->handle();
        /** @var InvoiceLineItem $lineItem */
        $lineItem = $invoice->lineItems->first();

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(60.0, $invoice->total);
        $this->assertEquals(2, $invoice->lineItems->count());

        $this->assertDatabaseHas('invoices', [
            'id'        => $invoice->id,
            'reference' => $invoice->reference
        ]);

        $this->assertDatabaseHas('invoice_line_items', [
            'id'          => $lineItem->id,
            'description' => 'Issue summary 1',
            'unit_amount' => 20.0
        ]);
    }
}
