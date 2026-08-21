<?php

namespace PhpOffice\PhpSpreadsheetTests\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;

class RelsRibbonTest extends TestCase
{
    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    /**
     * @var \PhpOffice\PhpSpreadsheet\Writer\Xlsx\RelsRibbon
     */
    private $writerPart;

    protected function setUp(): void
    {
        $this->spreadsheet = new Spreadsheet();
        $writer = new Xlsx($this->spreadsheet);
        $this->writerPart = $writer->getWriterPart('RelsRibbonObjects');
    }

    public function testNoRibbonObjects()
    {
        $xml = $this->writerPart->writeRibbonRelationships($this->spreadsheet);

        $relationships = simplexml_load_string($xml);

        self::assertSame('Relationships', $relationships->getName());
        self::assertCount(0, $relationships->children());
    }

    public function testRibbonObjectRelationshipsAreWritten()
    {
        $this->spreadsheet->setRibbonBinObjects(
            ['image1.png' => '../media/image1.png', 'image2.png' => '../media/image2.png'],
            ['image1.png' => 'dummy data', 'image2.png' => 'dummy data']
        );

        $xml = $this->writerPart->writeRibbonRelationships($this->spreadsheet);

        $relationships = simplexml_load_string($xml);

        self::assertCount(2, $relationships->Relationship);
        self::assertSame('image1.png', (string) $relationships->Relationship[0]['Id']);
        self::assertSame('../media/image1.png', (string) $relationships->Relationship[0]['Target']);
        self::assertSame(
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image',
            (string) $relationships->Relationship[0]['Type']
        );
        self::assertSame('image2.png', (string) $relationships->Relationship[1]['Id']);
        self::assertSame('../media/image2.png', (string) $relationships->Relationship[1]['Target']);
    }
}
