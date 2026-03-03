<?php

namespace App\Modules\Entity\Exceptions;

use RuntimeException;

class EntityNotFoundException extends RuntimeException
{
    public static function fromDocument(string $document): self
    {
        return new self(sprintf('Entity not found for document "%s".', $document));
    }
}
