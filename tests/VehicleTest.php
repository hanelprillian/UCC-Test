<?php

class VehicleTest extends TestCase
{
    /** @test */

    public function saveVehicleTest()
    {
        $this->json('POST', '/v1/vehicle/save',
            [
                'name' => 'Vehicle example',
                'location' => 'Singapore',
                'engine_power' => 500,
                'price' => 300,
                'displacement_unit' => 1,
                'bore' => 1,
                'stroke' => 3,
                'cylinders' => 2,
                'engine_displacement' => 0,
            ]
        )->getDataSetAsString();
    }
}
