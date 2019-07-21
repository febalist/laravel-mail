<?php

namespace Febalist\Laravel\Mail\Jobs;

use Exception;
use Febalist\Laravel\File\File;
use Febalist\Laravel\Mail\Mail\MarkdownMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class FileMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $file;
    protected $name;
    protected $email;
    protected $subject;
    protected $markdown;
    protected $replyTo;

    public function __construct($file, $name, $email, $subject = null, $markdown = null, $replyTo = null)
    {
        $this->file;
        $this->name;
        $this->email = $email;
        $this->subject = $subject;
        $this->markdown = $markdown;
        $this->replyTo = $replyTo;
    }

    public function handle()
    {
        $file = File::putTemp($this->file)->rename($this->name);

        try {
            $this->sendFile($file);
            $file->delete();
        } catch (Exception $exception) {
            if (str_contains($exception->getMessage(), 'Message exceeded max message size')) {
                $this->sendLink();
            } else {
                $file->delete();
                throw $exception;
            }
        }
    }

    protected function sendFile(File $file)
    {
        $mail = new MarkdownMail($this->subject, $this->markdown ?: $this->name);
        $mail->attach($file->file->local());

        if ($this->replyTo) {
            $mail->replyTo($this->replyTo);
        }

        Mail::to($this->email)->sendNow($mail);
    }

    protected function sendLink(File $file)
    {
        $mail = new MarkdownMail($this->subject, trim("$this->markdown\n\n[$this->name]({$file->downloadUrl()})"));

        if ($this->replyTo) {
            $mail->replyTo($this->replyTo);
        }

        Mail::to($this->email)->sendNow($mail);
    }
}
