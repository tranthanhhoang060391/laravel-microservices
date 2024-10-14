<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {

    function generateKey($serviceName, $environment) {
        // Create a base string that includes the service name and environment
        $baseString = $serviceName . '|' . $environment;

        // Use Laravel's Str::slug to normalize the string
        $normalizedString = Str::slug($baseString);

        // Generate a hash of the normalized string
        $serviceId = Hash::make($normalizedString);

        return $serviceId;
    }

    $serviceId = generateKey('api-gateway-service-id', 'production');
    $serviceSecret = generateKey('api-gateway-service-secret', 'production');

    var_dump($serviceId, $serviceSecret);exit;

    return view('welcome');
});
