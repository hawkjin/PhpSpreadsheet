<?php

namespace PhpOffice\PhpSpreadsheet\Chart;

/**
 * Created by PhpStorm.
 * User: Wiktor Trzonkowski
 * Date: 6/17/14
 * Time: 12:11 PM.
 */
class Axis extends Properties
{
    /**
     * Axis Number.
     *
     * @var array of mixed
     */
    private $axisNumber = [
        'format' => self::FORMAT_CODE_GENERAL,
        'source_linked' => 1,
    ];

    /**
     * Axis Options.
     *
     * @var array of mixed
     */
    private $axisOptions = [
        'minimum' => null,
        'maximum' => null,
        'major_unit' => null,
        'minor_unit' => null,
        'orientation' => self::ORIENTATION_NORMAL,
        'minor_tick_mark' => self::TICK_MARK_NONE,
        'major_tick_mark' => self::TICK_MARK_NONE,
        'axis_labels' => self::AXIS_LABELS_NEXT_TO,
        'horizontal_crosses' => self::HORIZONTAL_CROSSES_AUTOZERO,
        'horizontal_crosses_value' => null,
    ];

    /**
     * Fill Properties.
     *
     * @var array of mixed
     */
    private $fillProperties = [
        'type' => self::EXCEL_COLOR_TYPE_ARGB,
        'value' => null,
        'alpha' => 0,
    ];

    /**
     * Line Properties.
     *
     * @var array of mixed
     */
    private $lineProperties = [
        'type' => self::EXCEL_COLOR_TYPE_ARGB,
        'value' => null,
        'alpha' => 0,
    ];

    /**
     * Line Style Properties.
     *
     * @var array of mixed
     */
    private $lineStyleProperties = [
        'width' => '9525',
        'compound' => self::LINE_STYLE_COMPOUND_SIMPLE,
        'dash' => self::LINE_STYLE_DASH_SOLID,
        'cap' => self::LINE_STYLE_CAP_FLAT,
        'join' => self::LINE_STYLE_JOIN_BEVEL,
        'arrow' => [
            'head' => [
                'type' => self::LINE_STYLE_ARROW_TYPE_NOARROW,
                'size' => self::LINE_STYLE_ARROW_SIZE_5,
            ],
            'end' => [
                'type' => self::LINE_STYLE_ARROW_TYPE_NOARROW,
                'size' => self::LINE_STYLE_ARROW_SIZE_8,
            ],
        ],
    ];

    /**
     * Glow Properties.
     *
     * @var array of mixed
     */
    private $glowProperties = [
        'size' => null,
        'color' => [
            'type' => self::EXCEL_COLOR_TYPE_STANDARD,
            'value' => 'black',
            'alpha' => 40,
        ],
    ];

    /**
     * Get Series Data Type.
     *
     * @param mixed $format_code
     *
     * @return string
     */
    public function setAxisNumberProperties($format_code)
    {
        $this->axisNumber['format'] = (string) $format_code;
        $this->axisNumber['source_linked'] = 0;
    }

    /**
     * Get Axis Number Format Data Type.
     *
     * @return string
     */
    public function getAxisNumberFormat()
    {
        return $this->axisNumber['format'];
    }

    /**
     * Get Axis Number Source Linked.
     *
     * @return string
     */
    public function getAxisNumberSourceLinked()
    {
        return (string) $this->axisNumber['source_linked'];
    }

    /**
     * Set Axis Options Properties.
     *
     * @param string $axis_labels
     * @param string $horizontal_crosses_value
     * @param string $horizontal_crosses
     * @param string $axis_orientation
     * @param string $major_tmt
     * @param string $minor_tmt
     * @param string $minimum
     * @param string $maximum
     * @param string $major_unit
     * @param string $minor_unit
     */
    public function setAxisOptionsProperties($axis_labels, $horizontal_crosses_value = null, $horizontal_crosses = null, $axis_orientation = null, $major_tmt = null, $minor_tmt = null, $minimum = null, $maximum = null, $major_unit = null, $minor_unit = null)
    {
        $this->axisOptions['axis_labels'] = (string) $axis_labels;
        ($horizontal_crosses_value !== null) ? $this->axisOptions['horizontal_crosses_value'] = (string) $horizontal_crosses_value : null;
        ($horizontal_crosses !== null) ? $this->axisOptions['horizontal_crosses'] = (string) $horizontal_crosses : null;
        ($axis_orientation !== null) ? $this->axisOptions['orientation'] = (string) $axis_orientation : null;
        ($major_tmt !== null) ? $this->axisOptions['major_tick_mark'] = (string) $major_tmt : null;
        ($minor_tmt !== null) ? $this->axisOptions['minor_tick_mark'] = (string) $minor_tmt : null;
        ($minor_tmt !== null) ? $this->axisOptions['minor_tick_mark'] = (string) $minor_tmt : null;
        ($minimum !== null) ? $this->axisOptions['minimum'] = (string) $minimum : null;
        ($maximum !== null) ? $this->axisOptions['maximum'] = (string) $maximum : null;
        ($major_unit !== null) ? $this->axisOptions['major_unit'] = (string) $major_unit : null;
        ($minor_unit !== null) ? $this->axisOptions['minor_unit'] = (string) $minor_unit : null;
    }

    /**
     * Get Axis Options Property.
     *
     * @param string $property
     *
     * @return string
     */
    public function getAxisOptionsProperty($property)
    {
        return $this->axisOptions[$property];
    }

    /**
     * Set Axis Orientation Property.
     *
     * @param string $orientation
     */
    public function setAxisOrientation($orientation)
    {
        $this->axisOptions['orientation'] = (string) $orientation;
    }

    /**
     * Set Fill Property.
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     */
    public function setFillParameters($color, $alpha = 0, $type = self::EXCEL_COLOR_TYPE_ARGB)
    {
        $this->fillProperties = $this->setColorProperties($color, $alpha, $type);
    }

    /**
     * Set Line Property.
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     */
    public function setLineParameters($color, $alpha = 0, $type = self::EXCEL_COLOR_TYPE_ARGB)
    {
        $this->lineProperties = $this->setColorProperties($color, $alpha, $type);
    }

    /**
     * Get Fill Property.
     *
     * @param string $property
     *
     * @return string
     */
    public function getFillProperty($property)
    {
        return $this->fillProperties[$property];
    }

    /**
     * Get Line Property.
     *
     * @param string $property
     *
     * @return string
     */
    public function getLineProperty($property)
    {
        return $this->lineProperties[$property];
    }

    /**
     * Set Line Style Properties.
     *
     * @param float $line_width
     * @param string $compound_type
     * @param string $dash_type
     * @param string $cap_type
     * @param string $join_type
     * @param string $head_arrow_type
     * @param string $head_arrow_size
     * @param string $end_arrow_type
     * @param string $end_arrow_size
     */
    public function setLineStyleProperties($line_width = null, $compound_type = null, $dash_type = null, $cap_type = null, $join_type = null, $head_arrow_type = null, $head_arrow_size = null, $end_arrow_type = null, $end_arrow_size = null)
    {
        ($line_width !== null) ? $this->lineStyleProperties['width'] = $this->getExcelPointsWidth((float) $line_width) : null;
        ($compound_type !== null) ? $this->lineStyleProperties['compound'] = (string) $compound_type : null;
        ($dash_type !== null) ? $this->lineStyleProperties['dash'] = (string) $dash_type : null;
        ($cap_type !== null) ? $this->lineStyleProperties['cap'] = (string) $cap_type : null;
        ($join_type !== null) ? $this->lineStyleProperties['join'] = (string) $join_type : null;
        ($head_arrow_type !== null) ? $this->lineStyleProperties['arrow']['head']['type'] = (string) $head_arrow_type : null;
        ($head_arrow_size !== null) ? $this->lineStyleProperties['arrow']['head']['size'] = (string) $head_arrow_size : null;
        ($end_arrow_type !== null) ? $this->lineStyleProperties['arrow']['end']['type'] = (string) $end_arrow_type : null;
        ($end_arrow_size !== null) ? $this->lineStyleProperties['arrow']['end']['size'] = (string) $end_arrow_size : null;
    }

    /**
     * Get Line Style Property.
     *
     * @param array|string $elements
     *
     * @return string
     */
    public function getLineStyleProperty($elements)
    {
        return $this->getArrayElementsValue($this->lineStyleProperties, $elements);
    }

    /**
     * Get Line Style Arrow Excel Width.
     *
     * @param string $arrow
     *
     * @return string
     */
    public function getLineStyleArrowWidth($arrow)
    {
        return $this->getLineStyleArrowSize($this->lineStyleProperties['arrow'][$arrow]['size'], 'w');
    }

    /**
     * Get Line Style Arrow Excel Length.
     *
     * @param string $arrow
     *
     * @return string
     */
    public function getLineStyleArrowLength($arrow)
    {
        return $this->getLineStyleArrowSize($this->lineStyleProperties['arrow'][$arrow]['size'], 'len');
    }

    /**
     * Set Shadow Properties.
     *
     * @param int $sh_presets
     * @param string $sh_color_value
     * @param string $sh_color_type
     * @param string $sh_color_alpha
     * @param float $sh_blur
     * @param int $sh_angle
     * @param float $sh_distance
     */
    public function setShadowProperties($sh_presets, $sh_color_value = null, $sh_color_type = null, $sh_color_alpha = null, $sh_blur = null, $sh_angle = null, $sh_distance = null)
    {
        $this->setShadowPresetsProperties((int) $sh_presets)
            ->setShadowColor(
                $sh_color_value === null ? $this->shadowProperties['color']['value'] : $sh_color_value,
                $sh_color_alpha === null ? (int) $this->shadowProperties['color']['alpha'] : $sh_color_alpha,
                $sh_color_type === null ? $this->shadowProperties['color']['type'] : $sh_color_type
            )
            ->setShadowBlur($sh_blur)
            ->setShadowAngle($sh_angle)
            ->setShadowDistance($sh_distance);
    }

    /**
     * Set Shadow Color.
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     *
     * @return $this
     */
    private function setShadowColor($color, $alpha, $type)
    {
        $this->shadowProperties['color'] = $this->setColorProperties($color, $alpha, $type);

        return $this;
    }

    /**
     * Set Glow Properties.
     *
     * @param float $size
     * @param string $color_value
     * @param int $color_alpha
     * @param string $color_type
     */
    public function setGlowProperties($size, $color_value = null, $color_alpha = null, $color_type = null)
    {
        $this->setGlowSize($size)
            ->setGlowColor(
                $color_value === null ? $this->glowProperties['color']['value'] : $color_value,
                $color_alpha === null ? (int) $this->glowProperties['color']['alpha'] : $color_alpha,
                $color_type === null ? $this->glowProperties['color']['type'] : $color_type
            );
    }

    /**
     * Get Glow Property.
     *
     * @param array|string $property
     *
     * @return string
     */
    public function getGlowProperty($property)
    {
        return $this->getArrayElementsValue($this->glowProperties, $property);
    }

    /**
     * Set Glow Color.
     *
     * @param float $size
     *
     * @return $this
     */
    private function setGlowSize($size)
    {
        if ($size !== null) {
            $this->glowProperties['size'] = $this->getExcelPointsWidth($size);
        }

        return $this;
    }

    /**
     * Set Glow Color.
     *
     * @param string $color
     * @param int $alpha
     * @param string $type
     *
     * @return $this
     */
    private function setGlowColor($color, $alpha, $type)
    {
        $this->glowProperties['color'] = $this->setColorProperties($color, $alpha, $type);

        return $this;
    }

    /**
     * Set Soft Edges Size.
     *
     * @param float $size
     */
    public function setSoftEdges($size)
    {
        if ($size !== null) {
            $softEdges['size'] = (string) $this->getExcelPointsWidth($size);
        }
    }
}
