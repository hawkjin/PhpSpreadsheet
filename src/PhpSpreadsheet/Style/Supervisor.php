<?php

namespace PhpOffice\PhpSpreadsheet\Style;

use PhpOffice\PhpSpreadsheet\IComparable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class Supervisor implements IComparable
{
    /**
     * Supervisor?
     *
     * @var bool
     */
    protected $isSupervisor;

    /**
     * Parent. Only used for supervisor.
     *
     * @var Spreadsheet|Style
     */
    protected $parent;

    /**
     * Parent property name.
     *
     * @var null|string
     */
    protected $parentPropertyName;

    /**
     * Create a new Supervisor.
     *
     * @param bool $isSupervisor Flag indicating if this is a supervisor or not
     *                                    Leave this value at default unless you understand exactly what
     *                                        its ramifications are
     */
    public function __construct($isSupervisor = false)
    {
        // Supervisor?
        $this->isSupervisor = $isSupervisor;
    }

    /**
     * Bind parent. Only used for supervisor.
     *
     * @param Spreadsheet|Style $parent
     * @param null|string $parentPropertyName
     *
     * @return $this
     */
    public function bindParent($parent, $parentPropertyName = null)
    {
        $this->parent = $parent;
        $this->parentPropertyName = $parentPropertyName;

        return $this;
    }

    /**
     * Is this a supervisor or a cell style component?
     *
     * @return bool
     */
    public function getIsSupervisor()
    {
        return $this->isSupervisor;
    }

    /**
     * Get the currently active sheet. Only used for supervisor.
     *
     * @return Worksheet
     */
    public function getActiveSheet()
    {
        return $this->parent->getActiveSheet();
    }

    /**
     * Get the currently active cell coordinate in currently active sheet.
     * Only used for supervisor.
     *
     * @return string E.g. 'A1'
     */
    public function getSelectedCells()
    {
        return $this->getActiveSheet()->getSelectedCells();
    }

    /**
     * Get the currently active cell coordinate in currently active sheet.
     * Only used for supervisor.
     *
     * @return string E.g. 'A1'
     */
    public function getActiveCell()
    {
        return $this->getActiveSheet()->getActiveCell();
    }

    /**
     * Build the style array from the supplied values, and apply it to the cells that are
     * currently selected in the active sheet. Only used for supervisor.
     *
     * @param array $styleValues
     */
    protected function applyStyleToSelectedCells(array $styleValues)
    {
        $this->getActiveSheet()->getStyle($this->getSelectedCells())
            ->applyFromArray($this->getStyleArray($styleValues));
    }

    /**
     * Resolve the color to store in a style component, propagating it to the currently
     * selected cells when this is a supervisor.
     *
     * @param Color $newColor color being assigned, possibly a supervisor
     * @param Color $currentColor color currently held by the style component
     *
     * @return Color color that the style component should hold
     */
    protected function assignColor(Color $newColor, Color $currentColor)
    {
        // make sure parameter is a real color and not a supervisor
        $color = $newColor->getIsSupervisor() ? $newColor->getSharedComponent() : $newColor;

        if ($this->isSupervisor) {
            $this->getActiveSheet()->getStyle($this->getSelectedCells())
                ->applyFromArray($currentColor->getStyleArray(['argb' => $color->getARGB()]));

            return $currentColor;
        }

        return $color;
    }

    /**
     * Build style array from subcomponents.
     *
     * @param array $array
     *
     * @return array
     */
    abstract public function getStyleArray($array);

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ((is_object($value)) && ($key != 'parent')) {
                $this->$key = clone $value;
            } else {
                $this->$key = $value;
            }
        }
    }
}
