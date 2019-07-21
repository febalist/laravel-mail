<?php

namespace Febalist\Laravel\Mail;

use Febalist\Laravel\Mail\Jobs\FileMail;
use Febalist\Laravel\Mail\Mail\MarkdownMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Mail;

/** @mixin Model */
trait HasEmail
{
    public function sendMail(Mailable $mail)
    {
        Mail::to($this->email)->send($mail);
    }

    public function sendMarkdownMail($subject, $markdown, $replyTo = null)
    {
        $mail = new MarkdownMail($subject, $markdown);

        if ($replyTo) {
            $mail->replyTo($replyTo);
        }

        return Mail::to($this->email)->queue($mail);
    }

    public function sendFileMail($file, $name, $subject, $markdown, $replyTo = null)
    {
        return dispatch(new FileMail($file, $name, $this->email, $subject, $markdown, $replyTo));
    }
}
