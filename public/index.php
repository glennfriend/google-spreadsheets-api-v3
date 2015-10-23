<?php
#!/usr/bin/php -q

if (PHP_SAPI !== 'cli') {
    if ( '192.168.' !== substr($_SERVER['REMOTE_ADDR'],0,8) )
    {
        exit;
    }
    echo "<pre>\n";
}

$basePath = dirname(__DIR__);
require_once $basePath . '/app/bootstrap.php';
initialize($basePath);

require_once $basePath . '/app/library/GoogleWorksheetManager.php';
require_once $basePath . '/app/helper/GoogleApiHelper.php';




perform();
exit;

/**
 * 
 */
function perform()
{
    if ( phpversion() < '5.5' ) {
        show("PHP Version need >= 5.5");
        exit;
    }
    Log::record('start PHP '. phpversion() );

    //
    upgradeGoogleSheet();

    Log::record('Done');
    show("done");
}

/**
 *
 */
function upgradeGoogleSheet()
{
    $token = GoogleApiHelper::getToken();
    if ( !$token ) {
        show('token error!');
        exit;
    }

    $worksheet = GoogleApiHelper::getWorksheet( conf('google.spreadsheets.book'), conf('google.spreadsheets.sheet') , $token );
    if ( !$worksheet ) {
        show('worksheet not found!');
        exit;
    }

    $sheet = new GoogleWorksheetManager($worksheet);
    $header = $sheet->getHeader();
    $count = $sheet->getCount();
    for ( $i=0; $i<$count; $i++ ) {
        $row = $sheet->getRow($i);
        pr($row);
        // $sheet->setRow($i, $row);

        // debug
        show( $i . ' ' );
        if (PHP_SAPI !== 'cli') {
            ob_flush(); flush();
        }
    }
}

