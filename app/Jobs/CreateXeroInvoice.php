<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Jobs;

use App\Invoices\InteractsWithInvoiceModel;
use App\Invoices\InteractsWithXeroApi;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use XeroAPI\XeroPHP\Api\AccountingApi;
use XeroAPI\XeroPHP\Models\Accounting\Invoice as XeroInvoice;
use XeroAPI\XeroPHP\Models\Accounting\LineItem as XeroLineItem;

class CreateXeroInvoice implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithXeroApi,
        InteractsWithInvoiceModel;

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
     * @return XeroInvoice
     */
    public function handle(): ?XeroInvoice
    {
        if ($this->invoice->xero_id) {
            return null;
        }

        return tap(
            $this->createXeroInvoice($this->invoice),
            function (XeroInvoice $xeroInvoice) {
                $this->syncDataFromXeroInvoice($xeroInvoice);
            }
        );
    }

    public function syncDataFromXeroInvoice(XeroInvoice $xeroInvoice)
    {
        $this->invoice->setAttribute(
            'xero_id',
            $xeroInvoice->getInvoiceId()
        )->save();

        collect($xeroInvoice->getLineItems())->each(function (XeroLineItem $xeroLineItem) {
            $lineItem = $this->findLineItemByDescription($xeroLineItem->getDescription());

            if($lineItem) {
                $lineItem->xero_id = $xeroLineItem->getLineItemId();
                $lineItem->save();
            }
        });
    }
}
