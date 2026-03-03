<?php

namespace App\Modules\Admin\Exceptions\CityHall;

class CityHallRequestException extends CityHallException
{
    public static function failed(int $status, string $message = 'City hall request failed.'): self
    {
        return new self("{$message} HTTP {$status}");
    }

    public static function communicationError(string $message, ?\Throwable $previous = null): self
    {
        return new self($message, 0, $previous);
    }
}
