<?php

namespace Febalist\Laravel\Mail\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Parsedown;

class MarkdownMail extends Mailable
{
    use Queueable;

    public function __construct($subject, $markdown)
    {
        $this->subject = $subject;
        $this->html = Parsedown::instance()
            ->setBreaksEnabled(true)
            ->setUrlsLinked(true)->text($markdown);
    }

    public function build()
    {
        return $this;
    }
}
