<?php

namespace PhpOffice\PhpSpreadsheetTests\Chart;

use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PHPUnit\Framework\TestCase;

class GridLinesTest extends TestCase
{
    /**
     * @var GridLines
     */
    private $gridLines;

    protected function setUp(): void
    {
        $this->gridLines = new GridLines();
    }

    public function testObjectStateIsInactiveUntilAPropertyIsSet()
    {
        self::assertFalse($this->gridLines->getObjectState());

        $this->gridLines->setLineColorProperties('accent1', 20, Properties::EXCEL_COLOR_TYPE_SCHEME);

        self::assertTrue($this->gridLines->getObjectState());
    }

    public function testLineColorProperties()
    {
        $this->gridLines->setLineColorProperties('FF0000', 25, Properties::EXCEL_COLOR_TYPE_ARGB);

        self::assertSame(Properties::EXCEL_COLOR_TYPE_ARGB, $this->gridLines->getLineColorProperty('type'));
        self::assertSame('FF0000', $this->gridLines->getLineColorProperty('value'));
        self::assertSame('75000', $this->gridLines->getLineColorProperty('alpha'));
    }

    public function testDefaultLineStyleProperties()
    {
        self::assertSame('9525', $this->gridLines->getLineStyleProperty('width'));
        self::assertSame(Properties::LINE_STYLE_COMPOUND_SIMPLE, $this->gridLines->getLineStyleProperty('compound'));
        self::assertSame(Properties::LINE_STYLE_DASH_SOLID, $this->gridLines->getLineStyleProperty('dash'));
        self::assertSame(Properties::LINE_STYLE_CAP_FLAT, $this->gridLines->getLineStyleProperty('cap'));
        self::assertSame(Properties::LINE_STYLE_JOIN_BEVEL, $this->gridLines->getLineStyleProperty('join'));
    }

    public function testLineStyleProperties()
    {
        $this->gridLines->setLineStyleProperties(
            2.5,
            Properties::LINE_STYLE_COMPOUND_DOUBLE,
            Properties::LINE_STYLE_DASH_DASH_DOT,
            Properties::LINE_STYLE_CAP_ROUND,
            Properties::LINE_STYLE_JOIN_MITER,
            Properties::LINE_STYLE_ARROW_TYPE_ARROW,
            Properties::LINE_STYLE_ARROW_SIZE_3,
            Properties::LINE_STYLE_ARROW_TYPE_OVAL,
            Properties::LINE_STYLE_ARROW_SIZE_7
        );

        self::assertEquals(31750, $this->gridLines->getLineStyleProperty('width'));
        self::assertSame(Properties::LINE_STYLE_COMPOUND_DOUBLE, $this->gridLines->getLineStyleProperty('compound'));
        self::assertSame(Properties::LINE_STYLE_DASH_DASH_DOT, $this->gridLines->getLineStyleProperty('dash'));
        self::assertSame(Properties::LINE_STYLE_CAP_ROUND, $this->gridLines->getLineStyleProperty('cap'));
        self::assertSame(Properties::LINE_STYLE_JOIN_MITER, $this->gridLines->getLineStyleProperty('join'));
        self::assertSame(
            Properties::LINE_STYLE_ARROW_TYPE_ARROW,
            $this->gridLines->getLineStyleProperty(['arrow', 'head', 'type'])
        );
        self::assertSame(
            Properties::LINE_STYLE_ARROW_TYPE_OVAL,
            $this->gridLines->getLineStyleProperty(['arrow', 'end', 'type'])
        );
    }

    public function testLineStyleArrowParameters()
    {
        $this->gridLines->setLineStyleProperties(
            null,
            null,
            null,
            null,
            null,
            Properties::LINE_STYLE_ARROW_TYPE_ARROW,
            Properties::LINE_STYLE_ARROW_SIZE_3,
            Properties::LINE_STYLE_ARROW_TYPE_OVAL,
            Properties::LINE_STYLE_ARROW_SIZE_7
        );

        self::assertSame('sm', $this->gridLines->getLineStyleArrowParameters('head', 'w'));
        self::assertSame('lg', $this->gridLines->getLineStyleArrowParameters('head', 'len'));
        self::assertSame('lg', $this->gridLines->getLineStyleArrowParameters('end', 'w'));
        self::assertSame('sm', $this->gridLines->getLineStyleArrowParameters('end', 'len'));
    }

    public function testGlowProperties()
    {
        $this->gridLines->setGlowProperties(3.0, 'FF0000', 10, Properties::EXCEL_COLOR_TYPE_ARGB);

        self::assertTrue($this->gridLines->getObjectState());
        self::assertEquals(38100, $this->gridLines->getGlowSize());
        self::assertSame('FF0000', $this->gridLines->getGlowColor('value'));
        self::assertSame('90000', $this->gridLines->getGlowColor('alpha'));
        self::assertSame(Properties::EXCEL_COLOR_TYPE_ARGB, $this->gridLines->getGlowColor('type'));
    }

    public function testGlowPropertiesKeepDefaultColorWhenOmitted()
    {
        $this->gridLines->setGlowProperties(1.0);

        self::assertSame('black', $this->gridLines->getGlowColor('value'));
        self::assertSame(40, $this->gridLines->getGlowColor('alpha'));
    }

    public function testShadowProperties()
    {
        $this->gridLines->setShadowProperties(
            Properties::SHADOW_PRESETS_OUTER_BOTTTOM_RIGHT,
            'FF0000',
            Properties::EXCEL_COLOR_TYPE_ARGB,
            15,
            2.0,
            45,
            1.5
        );

        self::assertTrue($this->gridLines->getObjectState());
        self::assertSame(
            Properties::SHADOW_PRESETS_OUTER_BOTTTOM_RIGHT,
            $this->gridLines->getShadowProperty('presets')
        );
        self::assertSame('outerShdw', $this->gridLines->getShadowProperty('effect'));
        self::assertSame('25400', $this->gridLines->getShadowProperty('blur'));
        self::assertSame('2700000', $this->gridLines->getShadowProperty('direction'));
        self::assertSame('19050', $this->gridLines->getShadowProperty('distance'));
        self::assertSame('tl', $this->gridLines->getShadowProperty('algn'));
        self::assertSame('FF0000', $this->gridLines->getShadowProperty(['color', 'value']));
        self::assertSame(Properties::EXCEL_COLOR_TYPE_ARGB, $this->gridLines->getShadowProperty(['color', 'type']));
    }

    public function testShadowPresetWithNestedSizeProperties()
    {
        $this->gridLines->setShadowProperties(Properties::SHADOW_PRESETS_OUTER_CENTER);

        self::assertSame('102000', $this->gridLines->getShadowProperty(['size', 'sx']));
        self::assertSame('102000', $this->gridLines->getShadowProperty(['size', 'sy']));
        self::assertSame('63500', $this->gridLines->getShadowProperty('blur'));
    }

    public function testSoftEdgesSize()
    {
        self::assertNull($this->gridLines->getSoftEdgesSize());

        $this->gridLines->setSoftEdgesSize(null);

        self::assertNull($this->gridLines->getSoftEdgesSize());
        self::assertFalse($this->gridLines->getObjectState());

        $this->gridLines->setSoftEdgesSize(2.0);

        self::assertSame('25400', $this->gridLines->getSoftEdgesSize());
        self::assertTrue($this->gridLines->getObjectState());
    }
}
