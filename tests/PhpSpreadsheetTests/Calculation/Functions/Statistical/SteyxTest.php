<?php

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Statistical;

use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical;
use PHPUnit\Framework\TestCase;

class SteyxTest extends TestCase
{
    protected function setUp(): void
    {
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_EXCEL);
    }

    /**
     * @dataProvider providerSTEYX
     *
     * @param mixed $expectedResult
     */
    public function testSTEYX($expectedResult, array $xargs, array $yargs)
    {
        $result = Statistical::STEYX($xargs, $yargs);
        $this->assertEqualsWithDelta($expectedResult, $result, 1E-12);
    }

    public function providerSTEYX()
    {
        return require 'data/Calculation/Statistical/STEYX.php';
    }
}
