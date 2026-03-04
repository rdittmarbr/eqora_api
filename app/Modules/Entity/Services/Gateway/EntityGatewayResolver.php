<?php

namespace App\Modules\Entity\Services\Gateway;

use App\Modules\Entity\Contracts\PartnerEntityGateway;
use App\Modules\Partner\Models\Partner;

class EntityGatewayResolver
{
    /**
     * @param  iterable<int, PartnerEntityGateway>  $gateways
     */
    public function __construct(private readonly iterable $gateways)
    {
    }

    public function resolve(?Partner $partner): PartnerEntityGateway
    {
        foreach ($this->gateways as $gateway) {
            if ($gateway->supports($partner)) {
                return $gateway;
            }
        }

        throw new \RuntimeException('No entity gateway configured for selected partner.');
    }
}
