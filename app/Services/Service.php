<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Service
{
    protected string $status = 'success';
    protected string $message = '';
    protected int|string $code = 200;

    protected function exception($exception, string $notFoundMessage = ''): self
    {
        if ($exception instanceof ModelNotFoundException) {
            $this->status = 'error';
            $this->code = 404;
            $this->message = $notFoundMessage;
        } else {
            $this->status = 'error';
            $this->code = $exception->getCode();
            $this->code = httpStatusCodeError($exception->getCode());
            $this->message = $exception->getMessage();
        }

        return $this;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function code(): int|string
    {
        return $this->code;
    }

    public function message(): string
    {
        return $this->message;
    }
}
