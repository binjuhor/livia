<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use Illuminate\Support\Collection;
use LogicException;
use Webfox\Xero\OauthCredentialManager;
use XeroAPI\XeroPHP\Api\AccountingApi;
use XeroAPI\XeroPHP\Models\Accounting\Contact;
use XeroAPI\XeroPHP\Models\Accounting\Invoice as XeroInvoice;
use XeroAPI\XeroPHP\Models\Accounting\Invoices;
use XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes;
use XeroAPI\XeroPHP\Models\Accounting\LineItem as XeroLineItem;

trait InteractsWithXeroApi
{
    protected ?OauthCredentialManager $xeroCredentials = null;

    protected ?AccountingApi $xeroAccountingApi = null;

    public function xeroCredentials(): OauthCredentialManager
    {
        if (is_null($this->xeroCredentials)) {
            $this->xeroCredentials = resolve(OauthCredentialManager::class);
        }

        return $this->xeroCredentials;
    }

    public function xeroAccountingApi(): AccountingApi
    {
        if (is_null($this->xeroCredentials)) {
            $this->xeroAccountingApi = resolve(AccountingApi::class);
        }

        return $this->xeroAccountingApi;
    }

    public function findXeroInvoice(string $xeroId): XeroInvoice
    {
        $invoices = $this->xeroAccountingApi()->getInvoice(
            $this->xeroCredentials()->getTenantId(),
            $xeroId
        );

        return $this->getFirstXeroInvoice($invoices);
    }

    public function storeXeroInvoice(XeroInvoice $invoice): ?XeroInvoice
    {
        return $this->getFirstXeroInvoice(
            $this->xeroAccountingApi()->createInvoices(
                $this->xeroCredentials()->getTenantId(),
                new Invoices([
                    'invoices' => [$invoice]
                ])
            )
        );
    }

    public function getFirstXeroInvoice(Invoices $invoices): ?XeroInvoice
    {
        return $invoices->count() > 0 ?
            collect($invoices->getInvoices())->first()
            : null;
    }

    public function createXeroInvoice(Invoice $invoice): XeroInvoice
    {
        return $this->storeXeroInvoice(
            $this->makeXeroInvoice($invoice)
        );
    }

    public function updateXeroInvoice(Invoice $invoice): ?XeroInvoice
    {
        return $this->getFirstXeroInvoice(
            $this->xeroAccountingApi()->updateInvoice(
                $this->xeroCredentials()->getTenantId(),
                $invoice->xero_id,
                new Invoices([
                    'invoices' => [
                        $this->makeXeroInvoice($invoice)
                    ]
                ])
            )
        );
    }

    public function makeXeroInvoice(Invoice $invoice): XeroInvoice
    {
        $lineItems = $this->makeXeroLineItemsFromInvoice($invoice);
        $contact   = $this->makeXeroContact();
        return (new XeroInvoice)
            ->setInvoiceNumber($invoice->xero_id)
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

    public function makeXeroLineItemsFromInvoice(Invoice $invoice): Collection
    {
        $lineItems = collect();

        if ($invoice->lineItems->count()) {
            $invoice->lineItems->each(function (InvoiceLineItem $lineItem) use ($lineItems) {

                if ($lineItem->isFree()) {
                    return true;
                }

                $lineItems->push(
                    (new XeroLineItem)
                        ->setItemCode($lineItem->jira_key)
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

        throw new LogicException(__('This invoice has no item to process.'));
    }

    public function makeXeroContact(): Contact
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