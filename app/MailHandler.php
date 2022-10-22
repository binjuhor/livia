<?php

namespace App;

use App\Models\ReceivedMail;
use BeyondCode\Mailbox\InboundEmail;

class MailHandler {
    public function __invoke(InboundEmail $email): void
    {
        info($email->toArray());
        ReceivedMail::create([
            'sender'    => $email->from(),
            'subject'   => $email->subject(),
            'body'      => $email->text(),
        ]);
    }
}