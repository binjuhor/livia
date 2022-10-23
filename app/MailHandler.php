<?php

namespace App;

use App\Mail\TestMail;
use App\Models\ReceivedMail;
use BeyondCode\Mailbox\InboundEmail;
use Illuminate\Support\Str;

class MailHandler {
    public function __invoke(InboundEmail $email): void
    {
        ReceivedMail::create([
            'sender'    => $email->from(),
            'subject'   => $email->subject(),
            'body'      => $email->text(),
        ]);

        if(Str::contains($email->subject(), 'nvoice')) {
            ray("OK");
            $email->reply(new TestMail());
        }
    }
}