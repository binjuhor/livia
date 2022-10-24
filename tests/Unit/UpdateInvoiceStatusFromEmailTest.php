<?php

namespace Tests\Unit;

use App\Jobs\UpdateInvoiceStatusFromEmail;
use App\Models\ReceivedMail;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdateInvoiceStatusFromEmailTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_update_invoice_status_from_email()
    {
        $mail = ReceivedMail::find(1);
        $invoice = Invoice::find(63);

        (new UpdateInvoiceStatusFromEmail($mail))->handle();

        $mail->refresh();
        $invoice->refresh();

        $this->assertEquals(1, $mail->status);
        $this->assertNotNull($mail->logs);
        $this->assertEquals(2, $invoice->status);
    }
}
