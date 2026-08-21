<?php

namespace PhpOffice\PhpSpreadsheet\Writer\Ods;

class MetaInf extends WriterPart
{
    /**
     * Write META-INF/manifest.xml to XML format.
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     *
     * @return string XML Output
     */
    public function writeManifest()
    {
        $objWriter = $this->createXMLWriter();

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8');

        // Manifest
        $objWriter->startElement('manifest:manifest');
        $objWriter->writeAttribute('xmlns:manifest', 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0');
        $objWriter->writeAttribute('manifest:version', '1.2');

        $objWriter->startElement('manifest:file-entry');
        $objWriter->writeAttribute('manifest:full-path', '/');
        $objWriter->writeAttribute('manifest:version', '1.2');
        $objWriter->writeAttribute('manifest:media-type', 'application/vnd.oasis.opendocument.spreadsheet');
        $objWriter->endElement();
        $objWriter->startElement('manifest:file-entry');
        $objWriter->writeAttribute('manifest:full-path', 'meta.xml');
        $objWriter->writeAttribute('manifest:media-type', 'text/xml');
        $objWriter->endElement();
        $objWriter->startElement('manifest:file-entry');
        $objWriter->writeAttribute('manifest:full-path', 'settings.xml');
        $objWriter->writeAttribute('manifest:media-type', 'text/xml');
        $objWriter->endElement();
        $objWriter->startElement('manifest:file-entry');
        $objWriter->writeAttribute('manifest:full-path', 'content.xml');
        $objWriter->writeAttribute('manifest:media-type', 'text/xml');
        $objWriter->endElement();
        $objWriter->startElement('manifest:file-entry');
        $objWriter->writeAttribute('manifest:full-path', 'Thumbnails/thumbnail.png');
        $objWriter->writeAttribute('manifest:media-type', 'image/png');
        $objWriter->endElement();
        $objWriter->startElement('manifest:file-entry');
        $objWriter->writeAttribute('manifest:full-path', 'styles.xml');
        $objWriter->writeAttribute('manifest:media-type', 'text/xml');
        $objWriter->endElement();
        $objWriter->endElement();

        return $objWriter->getData();
    }
}
