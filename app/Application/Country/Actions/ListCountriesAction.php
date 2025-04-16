<?php

namespace App\Application\Country\Actions;

class ListCountriesAction
{
    public function __construct() {}

    public function execute(): Array
    {
        // Currently pass static array
        return [
            'AU' => 'Australia',
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'BR' => 'Brazil',
            'CA' => 'Canada',
            'CN' => 'China',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'FR' => 'France',
            'DE' => 'Germany',
            'IN' => 'India',
            'IT' => 'Italy',
            'JP' => 'Japan',
            'MX' => 'Mexico',
            'NL' => 'Netherlands',
            'NZ' => 'New Zealand',
            'NO' => 'Norway',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'RU' => 'Russia',
            'SG' => 'Singapore',
            'ZA' => 'South Africa',
            'ES' => 'Spain',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'TR' => 'Turkey',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
        ];
    }
}

