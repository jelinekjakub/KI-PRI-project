<?php // nahrát recept
require 'app/prolog.php';
require 'components/header.php';
require 'components/nav.php';
require 'components/boxes.php';
require_once 'app/xml.php';

use App\Xml;

if (!isUser())
    die; ?>

<div class="flex justify-center m-12">
    <form class="bg-zinc-50 rounded px-8 pt-6 pb-8 mb-4" enctype="multipart/form-data" method="POST">
        <div class="mb-4">
            Nahrajte recept:
        </div>
        <div class="mb-4">
            <input title="tt" id="fileInput" name="xml" type="file" accept="text/xml" data-max-file-size="2M">
        </div>
        <input class="bg-blue-500 text-white font-bold rounded py-2 px-4" type="submit" value="Odeslat" />
    </form>
</div>

<?php

if (($xmlFile = @$_FILES['xml']) && ($tmpName = @$xmlFile['tmp_name'])) {
    $isValid = Xml::validateXSD($tmpName, 'resources/xml/recept.xsd');
    if (!$isValid)
        errorBox('XML soubor není validní.');
    else {
        $drink = $xmlFile['name'];
        $target = "resources/drinks" . "/$drink";
        if (file_exists($target))
            errorBox('Recept už máme.');
        elseif (rename($tmpName, $target))
            successBox("OK - $drink - nahráno");

    }
}

require 'components/footer.php';
