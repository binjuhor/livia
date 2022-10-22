<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Jobs;

use App\Mail\Invoice as InvoiceMail;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Invoices\InteractsWithPdfInvoice;
use Illuminate\Support\Facades\Mail;

class SendEmailWithPdfInvoice implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithPdfInvoice;

    protected Invoice $invoice;

    /**
     * Create a new job instance.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle()
    {
        Mail::to('contact@photomart.com.au')
            ->bcc('son@chillbits.com')
            ->send(new InvoiceMail($this->invoice));
    }
}
