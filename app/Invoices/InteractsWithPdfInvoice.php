<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use LogicException;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice as PdfInvoice;
use LaravelDaily\Invoices\Classes\Party;

trait InteractsWithPdfInvoice
{
    /**
     * @throws BindingResolutionException
     */
    public function createPdfInvoice(Invoice $invoice): PdfInvoice
    {
        $client = new Party([
            'name' => 'Son Do',
            'phone' => '+84917499655',
            'custom_fields' => [
                'email' => 'son@chillbits.com'
            ]
        ]);
        $lineItems = $this->makePdfInvoiceLineItemsFromInvoice($invoice);
        $contact = $this->makePdfInvoiceContact();
        $notes = $this->makePdfInvoiceNote();
        $invoiceCode = explode('-', $invoice->reference)[0];
        $invoiceNumber = explode('-', $invoice->reference)[1];

        return PdfInvoice::make()
            ->series($invoiceCode)
            ->sequence($invoiceNumber)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->seller($client)
            ->buyer($contact)
            ->date(now())
            ->dateFormat('d/m/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('AUD')
            ->currencyFormat('{VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            ->filename($invoice->reference)
            ->addItems($lineItems->all())
            ->notes($notes)
            ->logo(public_path('logo.png'))
            ->save('public');
    }

    public function makePdfInvoiceNote(): string
    {
        return implode('<br>', [
            'AUD Bank Details',
            '---',
            'Account holder',
            'Ha son Do',
            'BSB code',
            '802-985',
            'Account number',
            '316418769',
            'Address',
            'Transferwise 36-38 Gipps Street Collingwood 3066 Australia'
        ]);
    }

    public function makePdfInvoiceLineItemsFromInvoice(Invoice $invoice): Collection
    {
        $lineItems = collect();
        if ($invoice->lineItems->count()) {
            $invoice->lineItems->each(function (InvoiceLineItem $lineItem) use ($lineItems) {

                if ($lineItem->isFree()) {
                    return true;
                }

                $lineItems->push(
                    (new InvoiceItem())
                        ->description($lineItem->description)
                        ->pricePerUnit($lineItem->unit_amount)
                        ->quantity($lineItem->quantity)
                );

                return true;
            });

            return $lineItems;
        }

        throw new LogicException(__('This invoice has no item to process.'));
    }

    public function makePdfInvoiceContact(): Buyer
    {
        return new Buyer([
            'name' => 'Brent Rasmussen',
            'custom_fields' => [
                'email' => 'contact@photomart.com.au',
                'address' => '54 Sunset Ave<br>BARRACK HEIGHTS NSW 2528<br>AUSTRALIA'
            ],
        ]);
    }
}