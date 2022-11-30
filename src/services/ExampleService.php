<?php

namespace App\services;

class ExampleService
{
    /**
     * @var CityService
     */
    protected CityService $cityService;

    public function __construct(
        CityService $cityService
    ) {
        $this->cityService = $cityService;
    }

    /**
     * return a random seller
     *
     * @return string
     * @throws \Exception
     */
    public function getSeller(): array
    {
        $names = [
            [
                'name' => 'James',
                'tel' => '0102030405',
                'cp' => '15000',
                'city' => 'Aurïll8ac',
            ],
            [
                'name' => 'Connor',
                'tel' => '0102030405',
                'cp' => '15000',
                'city' => 'Aurî88llac',
            ],
            [
                'name' => 'Andrew',
                'tel' => '0102030405',
                'cp' => '15100',
                'city' => 'Saint--F3loûr',
            ],
            [
                'name' => 'Phil',
                'tel' => '0102030405',
                'cp' => '63000',
                'city' => 'Clermond2-Ferrànd',
            ],
            [
                'name' => 'Séb',
                'tel' => '0707070707',
                'cp' => '69330',
                'city' => 'Paris',
            ],
        ];
        $seller = $names[random_int(0, count($names) - 1)];

        $seller['city_data'] = $this->cityService->getCity($seller['cp'], $seller['city']);



        return $seller;
    }
}