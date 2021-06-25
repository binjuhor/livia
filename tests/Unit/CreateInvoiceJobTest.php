<?php

namespace Tests\Unit;

use App\Jobs\CreateInvoice;
use App\Models\Invoice;
use App\Models\InvoiceItemLine;
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
        $invoice = (new CreateInvoice($this->project))->handle();
        /** @var InvoiceItemLine $itemLine */
        $itemLine = $invoice->itemLines->first();

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(60.0, $invoice->total);
        $this->assertEquals(2, $invoice->itemLines->count());

        $this->assertDatabaseHas('invoices', [
            'id'        => $invoice->id,
            'reference' => $invoice->reference
        ]);

        $this->assertDatabaseHas('invoice_item_lines', [
            'id'          => $itemLine->id,
            'description' => 'TEST-1: Issue summary 1',
            'unit_amount' => 20.0
        ]);
    }
}
