<?php

namespace App;

use DOMDocument, XSLTProcessor, DOMImplementation;

class Xml
{
    public static function listFiles($dir, $clean=false): array
    {
        $list = [];

        foreach (glob("$dir/*.xml") as $file)
        {
            if (!$clean)
            {
                $list[] = basename($file, ".xml");
            }
            else
            {
                $list[] = basename($file);
            }
        }

        return $list;
    }

    public static function validateName($name): bool
    {
        return !(in_array($name.'.xml', self::listFiles('resources/drinks', true)));
    }

    private static function printErrors(): void
    { ?>
        <table>
            <?php foreach (libxml_get_errors() as $error) { ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($error->line, ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($error->message, ENT_QUOTES, 'UTF-8') ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php
    }

    public static function validateDTD($xml, $dtd): bool
    {
        $doc = new DOMDocument;

        // Enable internal error handling
        libxml_use_internal_errors(true);
        $doc->loadXML(file_get_contents($xml));
        self::printErrors();

        // Check if document has root and DTD
        if ($doc->documentElement) {
            $root = $doc->documentElement->tagName;
            $systemId = 'data://text/plain;base64,' . base64_encode(file_get_contents($dtd));

            // Inject DTD into XML
            $creator = new DOMImplementation;
            $doctype = $creator->createDocumentType($root, '', $systemId);
            $newDoc = $creator->createDocument(null, '', $doctype);
            $newDoc->encoding = "utf-8";

            $newRootNode = $newDoc->importNode($doc->documentElement, true);
            $newDoc->appendChild($newRootNode);
            $doc = $newDoc;
        }

        // Validate against DTD
        $isValid = $doc->validate();
        self::printErrors();

        // Restore previous error handling
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        return $isValid;
    }

    public static function validateXSD($xml, $xsd): bool
    {
        $doc = new DOMDocument;

        // Enable internal error handling
        libxml_use_internal_errors(true);
        $doc->loadXML(file_get_contents($xml));
        self::printErrors();

        // Validate against XSD
        $isValid = $doc->schemaValidate($xsd);
        self::printErrors();

        // Restore previous error handling
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        return $isValid;
    }

    public static function transform($xml, $xsl)
    {
        $xmld = new DOMDocument;
        $xsld = new DOMDocument;
        $xslt = new XSLTProcessor();

        if (!$xmld->load($xml) || !$xsld->load($xsl) || !$xslt->importStylesheet($xsld)) {
            return false;
        }

        return $xslt->transformToXml($xmld);
    }
}
