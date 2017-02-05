<?php

require_once(LIBRARY . DS . 'phpqrcode' . DS . 'qrlib.php');

class BraveQRCode extends Brave {

    function gen($content, $filename=false, $errorCorrectionLevel='H', $matrixPointSize=5, $margin=2, $saveandprint=false) {
        if($filename) {
            $dir = dirname($filename);
            $BraveSystem = new BraveSystem();
            $BraveSystem->mkdirs($dir);
        }
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, $margin, $saveandprint);
    }

}