<?php

namespace Febalist\Laravel\Mail\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\View\View;
use Parsedown;

class MarkdownMail extends Mailable
{
    use Queueable;

    public function __construct($subject, $markdown)
    {
        $this->subject = $subject;
        $this->html = Parsedown::instance()
            ->setBreaksEnabled(true)
            ->setUrlsLinked(true)->text($markdown instanceof View ? $markdown->render() : $markdown);
    }

    public function build()
    {
        return $this;
    }
}
