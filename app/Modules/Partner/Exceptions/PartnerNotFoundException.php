<?php

namespace App\Modules\Partner\Exceptions;

use RuntimeException;

class PartnerNotFoundException extends RuntimeException
{
    public static function forTenant(int $tenantId, int $partnerId): self
    {
        return new self(sprintf('Partner %d was not found for tenant %d.', $partnerId, $tenantId));
    }
}
