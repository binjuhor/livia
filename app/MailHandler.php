<?php

namespace App;

use App\Jobs\ExtractTextFromImage;
use App\Mail\TestMail;
use App\Models\ReceivedMail;
use BeyondCode\Mailbox\InboundEmail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MailHandler {
    public function __invoke(InboundEmail $email): void
    {
        $email->save();
        ReceivedMail::create([
            'sender'    => $email->from(),
            'subject'   => $email->subject(),
            'body'      => $email->text(),
        ]);

        if(Str::contains($email->subject(), 'nvoice')) {
            $email->reply(new TestMail());
        }

        if(count($email->attachments())) {
            foreach ($email->attachments() as $attachment) {
                $filename = sprintf(
                    'app/public/%s-%s',
                    time(),
                    $attachment->getFilename()
                );

                $attachment->saveContent(storage_path($filename));

                dispatch(new ExtractTextFromImage($email->id, storage_path($filename)));
            }
        }
    }
}