<?php

namespace PhpOffice\PhpSpreadsheetTests\Shared\Trend;

use PhpOffice\PhpSpreadsheet\Shared\Trend\ExponentialBestFit;
use PHPUnit\Framework\TestCase;

class ExponentialBestFitTest extends TestCase
{
    /**
     * @var float
     */
    const DELTA = 1.0e-8;

    /**
     * @var ExponentialBestFit
     */
    private $bestFit;

    protected function setUp(): void
    {
        $xValues = [1, 2, 3, 4, 5];
        $yValues = [];
        foreach ($xValues as $xValue) {
            // Y = 2 * 3^X
            $yValues[] = 2 * pow(3, $xValue);
        }

        $this->bestFit = new ExponentialBestFit($yValues, $xValues);
    }

    public function testBestFitType()
    {
        self::assertSame('exponential', $this->bestFit->getBestFitType());
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
        self::assertSame('Y = 2 * 3^X', $this->bestFit->getEquation(2));
    }

    public function testValueOfYForX()
    {
        self::assertEqualsWithDelta(1458.0, $this->bestFit->getValueOfYForX(6), self::DELTA);
    }

    public function testValueOfXForY()
    {
        self::assertEqualsWithDelta(3.0, $this->bestFit->getValueOfXForY(54), self::DELTA);
    }

    public function testGoodnessOfFitForExactFit()
    {
        self::assertEqualsWithDelta(1.0, $this->bestFit->getGoodnessOfFit(), self::DELTA);
        self::assertEqualsWithDelta(100.0, $this->bestFit->getGoodnessOfFitPercent(), self::DELTA);
        self::assertEqualsWithDelta(1.0, $this->bestFit->getCorrelation(), self::DELTA);
        self::assertEqualsWithDelta(0.0, $this->bestFit->getSSResiduals(), self::DELTA);
        self::assertEqualsWithDelta(0.0, $this->bestFit->getStdevOfResiduals(), self::DELTA);
        self::assertEquals(3, $this->bestFit->getDFResiduals());
    }

    public function testValueSeries()
    {
        self::assertSame([1, 2, 3, 4, 5], $this->bestFit->getXValues());
        self::assertEqualsWithDelta([6, 18, 54, 162, 486], $this->bestFit->getYBestFitValues(), self::DELTA);
    }

    public function testMismatchedValueCountsAreFlaggedAsAnError()
    {
        $bestFit = new ExponentialBestFit([1, 2, 3], [1, 2]);

        self::assertTrue($bestFit->getError());
        // The regression is skipped, leaving the log-space slope at its default of 0
        self::assertEqualsWithDelta(exp(0), $bestFit->getSlope(), self::DELTA);
    }
}
