<?php

declare(strict_types = 1);

namespace App\Services;

use Jenssegers\Agent\Agent;

class DeviceService
{
    public static function getDeviceName(?string $userAgent): string
    {
        $agent = new Agent();
        $agent->setUserAgent(in_array($userAgent, [null, '', '0'], true) ? 'unknown' : $userAgent);

        $device      = $agent->device() ?: '';
        $deviceEmpty = ':';

        if (empty($device)) {
            $deviceEmpty = '';
        }

        $platform = $agent->platform() ?: 'Unknown OS';
        $browser  = $agent->browser() ?: 'Unknown Browser';

        return trim(sprintf('%s%s %s - %s', $device, $deviceEmpty, $platform, $browser));
    }
}
