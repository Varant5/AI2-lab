<?php

namespace App\Tests\Entity;

use App\Entity\MeasurementData;
use PHPUnit\Framework\TestCase;

class MeasurementDataTest extends TestCase
{
    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new MeasurementData();
        $measurement->setTemperature($celsius);

        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }

    public function dataGetFahrenheit(): array
    {
        return [
            [0, 32],
            [-100, -148],
            [100, 212],
            [0.5, 32.9],
            [5, 41],
            [37, 98.6],
            [6.5, 43.7],
            [20, 68],
            [15.5, 59.9],
            [10, 50],
        ];
    }
}