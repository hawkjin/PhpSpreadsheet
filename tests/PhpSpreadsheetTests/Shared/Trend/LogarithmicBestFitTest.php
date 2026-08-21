<?php

namespace PhpOffice\PhpSpreadsheetTests\Shared\Trend;

use PhpOffice\PhpSpreadsheet\Shared\Trend\LogarithmicBestFit;
use PHPUnit\Framework\TestCase;

class LogarithmicBestFitTest extends TestCase
{
    /**
     * @var float
     */
    const DELTA = 1.0e-8;

    /**
     * @var LogarithmicBestFit
     */
    private $bestFit;

    protected function setUp(): void
    {
        $xValues = [1, 2, 3, 4, 5];
        $yValues = [];
        foreach ($xValues as $xValue) {
            // Y = 5 + 2 * ln(X)
            $yValues[] = 5 + 2 * log($xValue);
        }

        $this->bestFit = new LogarithmicBestFit($yValues, $xValues);
    }

    public function testBestFitType()
    {
        self::assertSame('logarithmic', $this->bestFit->getBestFitType());
        self::assertFalse($this->bestFit->getError());
    }

    public function testRegressionCoefficients()
    {
        self::assertEqualsWithDelta(2.0, $this->bestFit->getSlope(), self::DELTA);
        self::assertEqualsWithDelta(5.0, $this->bestFit->getIntersect(), self::DELTA);
    }

    public function testEquation()
    {
        self::assertSame('Y = 5 + 2 * log(X)', $this->bestFit->getEquation(2));
    }

    public function testValueOfYForX()
    {
        self::assertEqualsWithDelta(5 + 2 * log(6), $this->bestFit->getValueOfYForX(6), self::DELTA);
    }

    public function testValueOfXForY()
    {
        // Y = 5 when ln(X) = 0
        self::assertEqualsWithDelta(1.0, $this->bestFit->getValueOfXForY(5), self::DELTA);
    }

    public function testGoodnessOfFitForExactFit()
    {
        self::assertEqualsWithDelta(1.0, $this->bestFit->getGoodnessOfFit(), self::DELTA);
        self::assertEqualsWithDelta(1.0, $this->bestFit->getCorrelation(), self::DELTA);
        self::assertEqualsWithDelta(0.0, $this->bestFit->getSSResiduals(), self::DELTA);
    }

    public function testValueSeries()
    {
        $expectedYValues = [];
        foreach ([1, 2, 3, 4, 5] as $xValue) {
            $expectedYValues[] = 5 + 2 * log($xValue);
        }

        self::assertSame([1, 2, 3, 4, 5], $this->bestFit->getXValues());
        self::assertEqualsWithDelta($expectedYValues, $this->bestFit->getYBestFitValues(), self::DELTA);
    }

    public function testMismatchedValueCountsAreFlaggedAsAnError()
    {
        $bestFit = new LogarithmicBestFit([1, 2, 3], [1, 2]);

        self::assertTrue($bestFit->getError());
    }
}
