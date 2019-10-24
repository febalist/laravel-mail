<?php

namespace Febalist\Laravel\Mail;

use Febalist\Laravel\Mail\Jobs\FileMail;
use Febalist\Laravel\Mail\Mail\HtmlMail;
use Febalist\Laravel\Mail\Mail\MarkdownMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Mail;

/** @mixin Model */
trait HasEmail
{
    public function sendMail(Mailable $mail)
    {
        Mail::to($this->email)->queue($mail);
    }

    public function sendMarkdownMail($subject, $markdown, callable $callback = null)
    {
        $mail = new MarkdownMail($subject, $markdown);

        if ($callback) {
            $callback($mail);
        }

        return $this->sendMail($mail);
    }

    public function sendHtmlMail($subject, $html, callable $callback = null)
    {
        $mail = new HtmlMail($subject, $html);

        if ($callback) {
            $callback($mail);
        }

        return $this->sendMail($mail);
    }

    public function sendFileMail($file, $name, $subject, $markdown, $replyTo = null)
    {
        return dispatch(new FileMail($file, $name, $this->email, $subject, $markdown, $replyTo));
    }
}
