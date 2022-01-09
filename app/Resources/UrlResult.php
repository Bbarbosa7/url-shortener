<?php

namespace App\Resources;

class UrlResult
{
    protected int $code;
    protected string $message;
    protected ?string $result;

    public function __construct(?string $result, int $code = 200, string $message = 'OK')
    {
        $this->code    = $code;
        $this->message = $message;
        $this->result  = $result ?? '';
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getResult(): string
    {
        return $this->result;
    }
}
