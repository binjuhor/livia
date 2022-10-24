<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App;

use App\Jobs\UpdateInvoiceStatusFromEmail;
use App\Models\ReceivedMail;
use BeyondCode\Mailbox\InboundEmail;
use Illuminate\Support\Str;

class MailHandler
{
    /** @noinspection PhpUndefinedMethodInspection */
    public function __invoke(InboundEmail $email): void
    {
        $email->save();

        $receivedMail = ReceivedMail::create([
            'sender' => $email->from(),
            'subject' => $email->subject(),
            'body' => $email->text(),
            'email_id' => $email->id,
            'status' => 0
        ]);

        $this->routing($receivedMail);
    }

    private function routing(ReceivedMail $mail): void
    {
        if(Str::contains($mail->subject, 'just paid you')) {
            dispatch(new UpdateInvoiceStatusFromEmail($mail));
        }
    }
}