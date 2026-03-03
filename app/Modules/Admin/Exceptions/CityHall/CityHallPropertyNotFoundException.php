<?php

namespace App\Modules\Admin\Exceptions\CityHall;

class CityHallPropertyNotFoundException extends CityHallException
{
    public static function forRegistration(string $registration): self
    {
        return new self("Property {$registration} not found at city hall.");
    }
}
