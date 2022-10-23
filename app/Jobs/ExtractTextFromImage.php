<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Jobs;

use App\Mail\TestMail;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Invoices\InteractsWithPdfInvoice;
use Illuminate\Support\Facades\Artisan;
use BeyondCode\Mailbox\InboundEmail;

class ExtractTextFromImage implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithPdfInvoice;

    protected $emailId;

    protected $filename;

    public function __construct(int $emailId, string $filename)
    {
        $this->emailId = $emailId;
        $this->filename = $filename;
    }

    public function handle()
    {
        $email = InboundEmail::find($this->emailId);
        Artisan::call('image', [
            'path' => $this->filename
        ]);

        $email->reply(new TestMail(Artisan::output()));
    }
}
