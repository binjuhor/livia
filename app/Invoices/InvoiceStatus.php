<?php


namespace App\Invoices;

use BenSampo\Enum\Enum;

final class InvoiceStatus extends Enum
{
    const Draft = 0;
    const Sent = 1;
    const Paid = 2;
}