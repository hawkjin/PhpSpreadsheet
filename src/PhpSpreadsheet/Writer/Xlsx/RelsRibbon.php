<?php

namespace PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class RelsRibbon extends WriterPart
{
    /**
     * Write relationships for additional objects of custom UI (ribbon).
     *
     * @param Spreadsheet $spreadsheet
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     *
     * @return string XML Output
     */
    public function writeRibbonRelationships(Spreadsheet $spreadsheet)
    {
        $objWriter = $this->createXMLWriter();

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $objWriter->startElement('Relationships');
        $objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');
        $localRels = $spreadsheet->getRibbonBinObjects('names');
        if (is_array($localRels)) {
            foreach ($localRels as $aId => $aTarget) {
                $objWriter->startElement('Relationship');
                $objWriter->writeAttribute('Id', $aId);
                $objWriter->writeAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image');
                $objWriter->writeAttribute('Target', $aTarget);
                $objWriter->endElement();
            }
        }
        $objWriter->endElement();

        return $objWriter->getData();
    }
}
