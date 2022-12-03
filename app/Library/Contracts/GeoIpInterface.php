<?php

namespace Acelle\Library\Contracts;

interface GeoIpInterface
{
    public function resolveIp($ip);
    public function getCountryCode();
    public function getCountryName();
    public function getRegionName();
    public function getCity();
    public function getZipcode();
    public function getLatitude();
    public function getLongitude();
    public function setup();
}
