<?php

namespace Febalist\Laravel\Mail\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\View\View;

class HtmlMail extends Mailable
{
    use Queueable;

    public function __construct($subject, $html)
    {
        $this->subject = $subject;
        $this->html = $html instanceof View ? $html->render() : $html;
    }

    public function build()
    {
        return $this;
    }
}
