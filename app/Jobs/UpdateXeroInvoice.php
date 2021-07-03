<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Jobs;

use App\Invoices\InteractsWithXeroApi;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use XeroAPI\XeroPHP\Models\Accounting\Invoice as XeroInvoice;


class UpdateXeroInvoice implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithXeroApi;

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
     * Execute the job.
     *
     * @return XeroInvoice|null
     */
    public function handle(): ?XeroInvoice
    {
        if (is_null($this->invoice->xero_id)) {
            return null;
        }

        return $this->updateXeroInvoice($this->invoice);
    }
}
