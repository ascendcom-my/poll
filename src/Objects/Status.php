<?php

namespace Bigmom\Poll\Objects;

class Status
{
    private $code;

    private $message;

    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function all()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }
}