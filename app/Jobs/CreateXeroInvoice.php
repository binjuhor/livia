<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Webfox\Xero\OauthCredentialManager;
use XeroAPI\XeroPHP\Api\AccountingApi;
use XeroAPI\XeroPHP\Models\Accounting\Contact;
use XeroAPI\XeroPHP\Models\Accounting\LineItem;
use XeroAPI\XeroPHP\Models\Accounting\Invoice as XeroInvoice;
use XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes;
use XeroAPI\XeroPHP\Models\Accounting\Invoices;

class CreateXeroInvoice implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

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
     * @return void
     */
    public function handle()
    {
        if ($this->invoice->xero_id) {
            return;
        }

        $xeroCredentials   = resolve(OauthCredentialManager::class);
        $xeroAccountingApi = resolve(AccountingApi::class);
        $xeroInvoice       = $this->createInvoice($this->invoice);

        /** @var Invoices $invoices */
        $invoices = $xeroAccountingApi->createInvoices(
            $xeroCredentials->getTenantId(),
            new Invoices([
                'invoices' => [$xeroInvoice]
            ])
        );

        if ($invoices->count()) {
            $xeroInvoice = collect(
                $invoices->getInvoices()
            )->first();

            $this->invoice->setAttribute(
                'xero_id',
                $xeroInvoice->getInvoiceId()
            )->save();
        }

        return $xeroInvoice;
    }

    public function createInvoice(Invoice $invoice): XeroInvoice
    {
        $lineItems = $this->createLineItemsFromInvoice($invoice);
        $contact   = $this->createContact();

        return (new XeroInvoice)
            ->setInvoiceNumber($invoice->reference ?: null)
            ->setDate(now())
            ->setDueDate(now()->addDays(7))
            ->setLineItems($lineItems->all())
            ->setStatus(XeroInvoice::STATUS_AUTHORISED)
            ->setType(XeroInvoice::TYPE_ACCREC)
            ->setLineAmountTypes(LineAmountTypes::INCLUSIVE)
            ->setContact($contact)
            ->setCurrencyCode('AUD');
    }

    public function createLineItemsFromInvoice(Invoice $invoice): Collection
    {
        $lineItems = collect();

        if ($invoice->lineItems->count()) {
            $invoice->lineItems->each(function (InvoiceLineItem $lineItem) use ($lineItems) {

                if($lineItem->isFree()) {
                    return true;
                }

                $lineItems->push(
                    (new LineItem)
                        ->setDescription($lineItem->description)
                        ->setQuantity($lineItem->quantity)
                        ->setUnitAmount($lineItem->unit_amount)
                        ->setAccountCode(200)
                        ->setTaxType('OUTPUT')
                );

                return true;
            });
            return $lineItems;
        }

        throw new \LogicException(__('This invoice has no item to process.'));
    }

    public function createContact(): Contact
    {
        // @TODO: Create contact database
        return (new Contact)
            ->setName('contact@photomart.com.au')
            ->setEmailAddress('contact@photomart.com.au')
            ->setFirstName('Brent')
            ->setLastName('Rasmussen')
            ->setContactStatus(Contact::CONTACT_STATUS_ACTIVE);
    }
}
