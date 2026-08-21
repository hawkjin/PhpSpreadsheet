<?php

namespace PhpOffice\PhpSpreadsheetTests\Shared\Trend;

use PhpOffice\PhpSpreadsheet\Shared\Trend\PowerBestFit;
use PHPUnit\Framework\TestCase;

class PowerBestFitTest extends TestCase
{
    /**
     * @var float
     */
    const DELTA = 1.0e-8;

    /**
     * @var PowerBestFit
     */
    private $bestFit;

    protected function setUp(): void
    {
        $xValues = [1, 2, 3, 4, 5];
        $yValues = [];
        foreach ($xValues as $xValue) {
            // Y = 2 * X^3
            $yValues[] = 2 * pow($xValue, 3);
        }

        $this->bestFit = new PowerBestFit($yValues, $xValues);
    }

    public function testBestFitType()
    {
        self::assertSame('power', $this->bestFit->getBestFitType());
        self::assertFalse($this->bestFit->getError());
    }

    public function testRegressionCoefficients()
    {
        self::assertEqualsWithDelta(3.0, $this->bestFit->getSlope(), self::DELTA);
        self::assertEqualsWithDelta(2.0, $this->bestFit->getIntersect(), self::DELTA);
        self::assertSame(3.0, $this->bestFit->getSlope(2));
        self::assertSame(2.0, $this->bestFit->getIntersect(2));
    }

    public function testEquation()
    {
        self::assertSame('Y = 2 * X^3', $this->bestFit->getEquation(2));
    }

    public function testValueOfYForX()
    {
        self::assertEqualsWithDelta(432.0, $this->bestFit->getValueOfYForX(6), self::DELTA);
    }

    public function testValueOfXForY()
    {
        self::assertEqualsWithDelta(2.0, $this->bestFit->getValueOfXForY(16), self::DELTA);
    }

    public function testGoodnessOfFitForExactFit()
    {
        self::assertEqualsWithDelta(1.0, $this->bestFit->getGoodnessOfFit(), self::DELTA);
        self::assertEqualsWithDelta(1.0, $this->bestFit->getCorrelation(), self::DELTA);
        self::assertEqualsWithDelta(0.0, $this->bestFit->getSSResiduals(), self::DELTA);
    }

    public function testValueSeries()
    {
        self::assertSame([1, 2, 3, 4, 5], $this->bestFit->getXValues());
        self::assertEqualsWithDelta([2, 16, 54, 128, 250], $this->bestFit->getYBestFitValues(), self::DELTA);
    }

    public function testMismatchedValueCountsAreFlaggedAsAnError()
    {
        $bestFit = new PowerBestFit([1, 2, 3], [1, 2]);

        self::assertTrue($bestFit->getError());
    }
}
