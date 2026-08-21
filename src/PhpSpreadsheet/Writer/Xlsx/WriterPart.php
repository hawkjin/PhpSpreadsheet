<?php

namespace PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class WriterPart
{
    /**
     * Parent Xlsx object.
     *
     * @var Xlsx
     */
    private $parentWriter;

    /**
     * Get parent Xlsx object.
     *
     * @return Xlsx
     */
    public function getParentWriter()
    {
        return $this->parentWriter;
    }

    /**
     * Create a new XMLWriter, using the temporary storage settings of the parent writer.
     *
     * @return XMLWriter
     */
    protected function createXMLWriter()
    {
        return XMLWriter::createForWriter($this->getParentWriter());
    }

    /**
     * Set parent Xlsx object.
     *
     * @param Xlsx $pWriter
     */
    public function __construct(Xlsx $pWriter)
    {
        $this->parentWriter = $pWriter;
    }
}
