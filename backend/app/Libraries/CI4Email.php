<?php

namespace App\Libraries;

class CI4Email
{
    protected $from = '';
    protected $fromName = '';
    protected $to = '';
    protected $subject = '';
    protected $message = '';

    public function from(string $from, string $name = '')
    {
        $this->from = $from;
        $this->fromName = $name;
        return $this;
    }

    public function setFrom(string $from, string $name = '')
    {
        return $this->from($from, $name);
    }

    public function to(string $to)
    {
        $this->to = $to;
        return $this;
    }

    public function setTo(string $to)
    {
        return $this->to($to);
    }

    public function subject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setSubject(string $subject)
    {
        return $this->subject($subject);
    }

    public function message(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function setMessage(string $message)
    {
        return $this->message($message);
    }

    public function send(): bool
    {
        return true;
    }

    public function print_debugger(): string
    {
        return '';
    }
}
