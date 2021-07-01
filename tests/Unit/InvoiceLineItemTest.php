<?php

namespace Tests\Unit;

use App\Invoices\InteractsWithInvoiceModel;
use App\Models\InvoiceLineItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InvoiceLineItemTest extends TestCase
{
    use InteractsWithInvoiceModel,
        DatabaseTransactions;

    /**
     * Project basic model test
     *
     * @return void
     */
    public function test_invoice_line_item_instance()
    {
        /** @var InvoiceLineItem $lineItem */
        $lineItem = InvoiceLineItem::factory()->make([
            'quantity' => 0
        ]);

        $this->assertInstanceOf(InvoiceLineItem::class, $lineItem);
        $this->assertTrue($lineItem->isFree());
    }

    public function test_invoice_line_item_find_by_description()
    {
        /** @var InvoiceLineItem $lineItem */
        $lineItem = InvoiceLineItem::factory()->create([
            'description' => '(TEST-1) Test Summary 1'
        ]);

        $foundLineItem = $this->findLineItemByDescription('(TEST-1) Test Summary 1 2 3');

        $this->assertInstanceOf(InvoiceLineItem::class, $foundLineItem);
        $this->assertEquals($lineItem->id, $foundLineItem->id);
    }
}
