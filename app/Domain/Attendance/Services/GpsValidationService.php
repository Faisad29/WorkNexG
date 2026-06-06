<?php

namespace App\Domain\Attendance\Services;

use App\Domain\Site\Models\Site;

class GpsValidationService
{
    public function isWithinRadius(Site $site, float $latitude, float $longitude): bool
    {
        $earthRadiusMeters = 6371000;

        $latitudeFrom = deg2rad((float) $site->latitude);
        $longitudeFrom = deg2rad((float) $site->longitude);
        $latitudeTo = deg2rad($latitude);
        $longitudeTo = deg2rad($longitude);

        $latitudeDelta = $latitudeTo - $latitudeFrom;
        $longitudeDelta = $longitudeTo - $longitudeFrom;

        $a = sin($latitudeDelta / 2) ** 2
            + cos($latitudeFrom) * cos($latitudeTo) * sin($longitudeDelta / 2) ** 2;

        $distance = 2 * $earthRadiusMeters * asin(min(1, sqrt($a)));

        return $distance <= (float) $site->radius_meters;
    }
}
