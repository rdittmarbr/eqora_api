<?php

namespace App\Modules\Device\Exceptions;

use RuntimeException;

class DeviceNotFoundException extends RuntimeException
{
    public static function forTenant(int $tenantId, int $deviceId): self
    {
        return new self(sprintf('Device %d was not found for tenant %d.', $deviceId, $tenantId));
    }
}
