<?php

namespace App\Mail;

use App\Models\Invoice as InvoiceModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Invoice extends Mailable
{
    use Queueable, SerializesModels;

    public InvoiceModel $invoice;

    public function __construct(InvoiceModel $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build(): Invoice
    {
        return $this->view('emails.invoice')
            ->subject(sprintf('Invoice %s from Chill Bits for contact@photomart.com.au', $this->invoice->reference))
            ->attachFromStorage('public/' . str_replace(url('/storage')  . '/', '', $this->invoice->pdf_file));
    }
}