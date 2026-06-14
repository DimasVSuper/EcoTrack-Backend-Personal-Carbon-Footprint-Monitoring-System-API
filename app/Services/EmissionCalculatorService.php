<?php

namespace App\Services;

use App\Models\TransportType;

class EmissionCalculatorService
{
    /**
     * Calculate emissions for electricity usage.
     * Assuming an average emission factor of 0.85 kg CO2 per kWh.
     * 
     * @param float $kwh
     * @return float
     */
    public function calculateElectricityEmission(float $kwh): float
    {
        $emissionFactor = 0.85; // kg CO2/kWh
        return $kwh * $emissionFactor;
    }

    /**
     * Calculate emissions for transport usage.
     * 
     * @param float $distanceKm
     * @param int $transportTypeId
     * @return float
     */
    public function calculateTransportEmission(float $distanceKm, int $transportTypeId): float
    {
        $transportType = TransportType::find($transportTypeId);
        
        if (!$transportType) {
            return 0.0;
        }

        return $distanceKm * $transportType->emission_factor_per_km;
    }
}
