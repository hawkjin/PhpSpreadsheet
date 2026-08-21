<?php

namespace PhpOffice\PhpSpreadsheetTests\Chart;

use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PHPUnit\Framework\TestCase;

class AxisTest extends TestCase
{
    /**
     * @var Axis
     */
    private $axis;

    protected function setUp(): void
    {
        $this->axis = new Axis();
    }

    public function testDefaultAxisNumberProperties()
    {
        self::assertSame(Properties::FORMAT_CODE_GENERAL, $this->axis->getAxisNumberFormat());
        self::assertSame('1', $this->axis->getAxisNumberSourceLinked());
    }

    public function testSetAxisNumberProperties()
    {
        $this->axis->setAxisNumberProperties(Properties::FORMAT_CODE_PERCENTAGE);

        self::assertSame(Properties::FORMAT_CODE_PERCENTAGE, $this->axis->getAxisNumberFormat());
        self::assertSame('0', $this->axis->getAxisNumberSourceLinked());
    }

    public function testDefaultAxisOptions()
    {
        self::assertNull($this->axis->getAxisOptionsProperty('minimum'));
        self::assertNull($this->axis->getAxisOptionsProperty('maximum'));
        self::assertSame(Properties::ORIENTATION_NORMAL, $this->axis->getAxisOptionsProperty('orientation'));
        self::assertSame(Properties::TICK_MARK_NONE, $this->axis->getAxisOptionsProperty('major_tick_mark'));
        self::assertSame(Properties::TICK_MARK_NONE, $this->axis->getAxisOptionsProperty('minor_tick_mark'));
        self::assertSame(Properties::AXIS_LABELS_NEXT_TO, $this->axis->getAxisOptionsProperty('axis_labels'));
        self::assertSame(
            Properties::HORIZONTAL_CROSSES_AUTOZERO,
            $this->axis->getAxisOptionsProperty('horizontal_crosses')
        );
    }

    public function testSetAxisOptionsProperties()
    {
        $this->axis->setAxisOptionsProperties(
            Properties::AXIS_LABELS_LOW,
            '5',
            Properties::HORIZONTAL_CROSSES_MAXIMUM,
            Properties::ORIENTATION_REVERSED,
            Properties::TICK_MARK_INSIDE,
            Properties::TICK_MARK_OUTSIDE,
            0,
            100,
            20,
            5
        );

        self::assertSame(Properties::AXIS_LABELS_LOW, $this->axis->getAxisOptionsProperty('axis_labels'));
        self::assertSame('5', $this->axis->getAxisOptionsProperty('horizontal_crosses_value'));
        self::assertSame(
            Properties::HORIZONTAL_CROSSES_MAXIMUM,
            $this->axis->getAxisOptionsProperty('horizontal_crosses')
        );
        self::assertSame(Properties::ORIENTATION_REVERSED, $this->axis->getAxisOptionsProperty('orientation'));
        self::assertSame(Properties::TICK_MARK_INSIDE, $this->axis->getAxisOptionsProperty('major_tick_mark'));
        self::assertSame(Properties::TICK_MARK_OUTSIDE, $this->axis->getAxisOptionsProperty('minor_tick_mark'));
        self::assertSame('0', $this->axis->getAxisOptionsProperty('minimum'));
        self::assertSame('100', $this->axis->getAxisOptionsProperty('maximum'));
        self::assertSame('20', $this->axis->getAxisOptionsProperty('major_unit'));
        self::assertSame('5', $this->axis->getAxisOptionsProperty('minor_unit'));
    }

    public function testSetAxisOptionsPropertiesKeepsDefaultsForOmittedValues()
    {
        $this->axis->setAxisOptionsProperties(Properties::AXIS_LABELS_NONE);

        self::assertSame(Properties::AXIS_LABELS_NONE, $this->axis->getAxisOptionsProperty('axis_labels'));
        self::assertNull($this->axis->getAxisOptionsProperty('minimum'));
        self::assertSame(Properties::ORIENTATION_NORMAL, $this->axis->getAxisOptionsProperty('orientation'));
    }

    public function testSetAxisOrientation()
    {
        $this->axis->setAxisOrientation(Properties::ORIENTATION_REVERSED);

        self::assertSame(Properties::ORIENTATION_REVERSED, $this->axis->getAxisOptionsProperty('orientation'));
    }

    public function testFillParameters()
    {
        $this->axis->setFillParameters('FF0000', 20);

        self::assertSame(Properties::EXCEL_COLOR_TYPE_ARGB, $this->axis->getFillProperty('type'));
        self::assertSame('FF0000', $this->axis->getFillProperty('value'));
        self::assertSame('80000', $this->axis->getFillProperty('alpha'));
    }

    public function testLineParameters()
    {
        $this->axis->setLineParameters('accent1', 0, Properties::EXCEL_COLOR_TYPE_SCHEME);

        self::assertSame(Properties::EXCEL_COLOR_TYPE_SCHEME, $this->axis->getLineProperty('type'));
        self::assertSame('accent1', $this->axis->getLineProperty('value'));
        self::assertSame('100000', $this->axis->getLineProperty('alpha'));
    }

    public function testDefaultLineStyleProperties()
    {
        self::assertSame('9525', $this->axis->getLineStyleProperty('width'));
        self::assertSame(Properties::LINE_STYLE_COMPOUND_SIMPLE, $this->axis->getLineStyleProperty('compound'));
        self::assertSame('med', $this->axis->getLineStyleArrowWidth('head'));
        self::assertSame('med', $this->axis->getLineStyleArrowLength('head'));
        self::assertSame('lg', $this->axis->getLineStyleArrowWidth('end'));
        self::assertSame('med', $this->axis->getLineStyleArrowLength('end'));
    }

    public function testSetLineStyleProperties()
    {
        $this->axis->setLineStyleProperties(
            1.5,
            Properties::LINE_STYLE_COMPOUND_TRIPLE,
            Properties::LINE_STYLE_DASH_LONG_DASH,
            Properties::LINE_STYLE_CAP_SQUARE,
            Properties::LINE_STYLE_JOIN_MITER,
            Properties::LINE_STYLE_ARROW_TYPE_STEALTH,
            Properties::LINE_STYLE_ARROW_SIZE_1,
            Properties::LINE_STYLE_ARROW_TYPE_DIAMOND,
            Properties::LINE_STYLE_ARROW_SIZE_9
        );

        self::assertEquals(19050, $this->axis->getLineStyleProperty('width'));
        self::assertSame(Properties::LINE_STYLE_COMPOUND_TRIPLE, $this->axis->getLineStyleProperty('compound'));
        self::assertSame(Properties::LINE_STYLE_DASH_LONG_DASH, $this->axis->getLineStyleProperty('dash'));
        self::assertSame(Properties::LINE_STYLE_CAP_SQUARE, $this->axis->getLineStyleProperty('cap'));
        self::assertSame(Properties::LINE_STYLE_JOIN_MITER, $this->axis->getLineStyleProperty('join'));
        self::assertSame(
            Properties::LINE_STYLE_ARROW_TYPE_STEALTH,
            $this->axis->getLineStyleProperty(['arrow', 'head', 'type'])
        );
        self::assertSame(
            Properties::LINE_STYLE_ARROW_TYPE_DIAMOND,
            $this->axis->getLineStyleProperty(['arrow', 'end', 'type'])
        );
        self::assertSame('sm', $this->axis->getLineStyleArrowWidth('head'));
        self::assertSame('lg', $this->axis->getLineStyleArrowWidth('end'));
    }

    public function testShadowProperties()
    {
        $this->axis->setShadowProperties(
            Properties::SHADOW_PRESETS_INNER_TOP_LEFT,
            'FF0000',
            Properties::EXCEL_COLOR_TYPE_ARGB,
            10,
            2.0,
            45,
            1.5
        );

        self::assertSame(
            Properties::SHADOW_PRESETS_INNER_TOP_LEFT,
            $this->axis->getShadowProperty('presets')
        );
        self::assertSame('innerShdw', $this->axis->getShadowProperty('effect'));
        self::assertSame('25400', $this->axis->getShadowProperty('blur'));
        self::assertSame('2700000', $this->axis->getShadowProperty('direction'));
        self::assertSame('19050', $this->axis->getShadowProperty('distance'));
        self::assertSame('FF0000', $this->axis->getShadowProperty(['color', 'value']));
        self::assertSame('90000', $this->axis->getShadowProperty(['color', 'alpha']));
        self::assertSame(Properties::EXCEL_COLOR_TYPE_ARGB, $this->axis->getShadowProperty(['color', 'type']));
    }

    public function testShadowPropertiesUseDefaultColorWhenOmitted()
    {
        $this->axis->setShadowProperties(Properties::SHADOW_PRESETS_OUTER_CENTER);

        self::assertSame('black', $this->axis->getShadowProperty(['color', 'value']));
        self::assertSame('60000', $this->axis->getShadowProperty(['color', 'alpha']));
        self::assertSame('102000', $this->axis->getShadowProperty(['size', 'sx']));
        self::assertNull($this->axis->getShadowProperty('direction'));
    }

    public function testGlowProperties()
    {
        $this->axis->setGlowProperties(2.0, 'FF0000', 15, Properties::EXCEL_COLOR_TYPE_ARGB);

        self::assertEquals(25400, $this->axis->getGlowProperty('size'));
        self::assertSame('FF0000', $this->axis->getGlowProperty(['color', 'value']));
        self::assertSame('85000', $this->axis->getGlowProperty(['color', 'alpha']));
        self::assertSame(Properties::EXCEL_COLOR_TYPE_ARGB, $this->axis->getGlowProperty(['color', 'type']));
    }

    public function testGlowPropertiesUseDefaultColorWhenOmitted()
    {
        $this->axis->setGlowProperties(1.0);

        self::assertEquals(12700, $this->axis->getGlowProperty('size'));
        self::assertSame('black', $this->axis->getGlowProperty(['color', 'value']));
        self::assertSame('60000', $this->axis->getGlowProperty(['color', 'alpha']));
    }

    public function testSoftEdgesSizeDefaultsToNull()
    {
        self::assertNull($this->axis->getSoftEdgesSize());
    }
}
