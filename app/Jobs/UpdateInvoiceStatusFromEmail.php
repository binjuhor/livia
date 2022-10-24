<?php

namespace App\Jobs;

use App\Invoices\InteractsWithInvoiceModel;
use App\Models\ReceivedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class UpdateInvoiceStatusFromEmail implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithInvoiceModel;

    protected ReceivedMail $mail;

    protected array $logs = [];

    public function __construct(ReceivedMail $mail)
    {
        $this->mail = $mail;
    }

    public function handle()
    {
        $amount = $this->parsingAmountFromMailBody();
        $invoice = $this->findRecentInvoiceByAmount($amount);

        if ($invoice) {
            $invoice->status = 2;
            $invoice->save();

            $this->logs[] = sprintf('Update invoice %d status successfully', $invoice->id);
            $this->mail->status = 1;
        } else {
            $this->logs[] = 'Could not find invoice with the given amount';
        }

        $this->updateMail();
    }

    private function parsingAmountFromMailBody(): int
    {
        preg_match('/(\d+) AUD/', $this->mail->body, $matches);

        if (Str::contains($matches[0], 'AUD') && is_numeric($matches[1])) {
            $this->logs[] = 'Found AUD amount';
            return $matches[1];
        }

        $this->logs[] = sprintf('Could not find AUD amount, matches: %s', json_encode($matches));

        return -1;
    }

    private function updateMail()
    {
        $this->mail->logs = json_encode($this->logs);
        $this->mail->save();
    }
}
