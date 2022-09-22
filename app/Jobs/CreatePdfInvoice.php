<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Invoices\InteractsWithPdfInvoice;
use LaravelDaily\Invoices\Invoice as PdfInvoice;

class CreatePdfInvoice implements ShouldQueue
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

    /**
     * @throws BindingResolutionException
     */
    public function handle()
    {
        return tap(
            $this->createPdfInvoice($this->invoice),
            function(PdfInvoice $invoice) {
                $this->invoice->setAttribute(
                    'pdf_file',
                    $invoice->url()
                )->save();
            }
        );
    }
}
