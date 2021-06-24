<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
     * @noinspection PhpUndefinedMethodInspection
     */
    public function handle()
    {
        /** @var  $xeroCredentials */
        $xeroCredentials = resolve(OauthCredentialManager::class);
        /** @var AccountingApi $xeroAccountingApi */
        $xeroAccountingApi = resolve(AccountingApi::class);
        tap(
            $this->createInvoice(),
            function(XeroInvoice $xeroInvoice) use ($xeroCredentials, $xeroAccountingApi) {
                $results = $xeroAccountingApi->createInvoices(
                    $xeroCredentials->getTenantId(),
                    new Invoices([$xeroInvoice])
                );

                dd($results);
            }
        );
    }

    public function createInvoice(): XeroInvoice
    {
        $lineItem = $this->createLineItem();
        $contact = $this->createContact();

        return (new XeroInvoice)
            ->setInvoiceNumber($this->invoice->reference ?: null)
            ->setDate(now())
            ->setDueDate(now()->addDays(7))
            ->setLineItems([$lineItem]) // @TODO: Make multiple lines later
            ->setStatus(XeroInvoice::STATUS_AUTHORISED)
            ->setType(XeroInvoice::TYPE_ACCREC)
            ->setLineAmountTypes(LineAmountTypes::INCLUSIVE)
            ->setContact($contact);
    }

    public function createLineItem(): LineItem
    {
        return (new LineItem)
            ->setDescription('Website development work')
            ->setQuantity(1)
            ->setUnitAmount($this->invoice->total ?? 0)
            ->setAccountCode(200)
            ->setTaxType('OUTPUT')
            ->setDiscountRate(0);
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
