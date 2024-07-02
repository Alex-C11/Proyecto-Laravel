<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Calculadora;
use Mockery;

class CalculadoraTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testSumaConMock()
    {
        $calculadora = Mockery::mock(Calculadora::class);
        $calculadora->shouldReceive('sumar')->once()->with(2, 3)->andReturn(5);

        $resultado = $calculadora->sumar(2, 3);

        $this->assertEquals(5, $resultado);
    }
}
