<?php

namespace PhpOffice\PhpSpreadsheetTests\Shared\Trend;

use PhpOffice\PhpSpreadsheet\Shared\Trend\ExponentialBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\LinearBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\LogarithmicBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\PowerBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\Trend;
use PHPUnit\Framework\TestCase;

class TrendTest extends TestCase
{
    /**
     * @dataProvider providerTrendTypes
     *
     * @param string $trendType
     * @param string $expectedClass
     * @param string $expectedBestFitType
     */
    public function testCalculateReturnsRequestedTrendType($trendType, $expectedClass, $expectedBestFitType)
    {
        $trend = Trend::calculate($trendType, [2, 4, 6, 8], [1, 2, 3, 4]);

        self::assertInstanceOf($expectedClass, $trend);
        self::assertSame($expectedBestFitType, $trend->getBestFitType());
    }

    public function providerTrendTypes()
    {
        return [
            [Trend::TREND_LINEAR, LinearBestFit::class, 'linear'],
            [Trend::TREND_LOGARITHMIC, LogarithmicBestFit::class, 'logarithmic'],
            [Trend::TREND_EXPONENTIAL, ExponentialBestFit::class, 'exponential'],
            [Trend::TREND_POWER, PowerBestFit::class, 'power'],
        ];
    }

    public function testUnknownTrendType()
    {
        self::assertFalse(Trend::calculate('Unknown', [2, 4, 6], [1, 2, 3]));
    }

    public function testXValuesDefaultToASequence()
    {
        $trend = Trend::calculate(Trend::TREND_LINEAR, [2, 4, 6, 8]);

        self::assertSame([1, 2, 3, 4], $trend->getXValues());
        self::assertEqualsWithDelta(2.0, $trend->getSlope(), 1.0e-8);
        self::assertEqualsWithDelta(0.0, $trend->getIntersect(), 1.0e-8);
    }

    public function testResultsAreCached()
    {
        $first = Trend::calculate(Trend::TREND_LINEAR, [2, 4, 6, 8], [1, 2, 3, 4]);
        $second = Trend::calculate(Trend::TREND_LINEAR, [2, 4, 6, 8], [1, 2, 3, 4]);
        $different = Trend::calculate(Trend::TREND_LINEAR, [3, 6, 9, 12], [1, 2, 3, 4]);

        self::assertSame($first, $second);
        self::assertNotSame($first, $different);
    }
}
