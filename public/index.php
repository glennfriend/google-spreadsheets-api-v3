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


require_once basePath() . '/app/library/GoogleWorksheetManager.php';
require_once basePath() . '/app/helper/GoogleApiHelper.php';
exit;




perform();
exit;

/**
 * 
 */
function perform()
{
    if ( phpversion() < '5.5' ) {
        echo "PHP Version need >= 5.5";
        echo "\n";
        exit;
    }
    Log::record(' - start PHP '. phpversion() );

    //
    upgradeGoogleSheet();

    Log::record(' - Done');
    echo "done\n";
}

/**
 *
 */
function upgradeGoogleSheet()
{
    $token = GoogleApiHelper::getToken();
    if ( !$token ) {
        die('token error!');
    }

    $worksheet = GoogleApiHelper::getWorksheet( conf('google.spreadsheets.book'), conf('google.spreadsheets.sheet') , $token );
    if ( !$worksheet ) {
        die('worksheet not found!');
    }

    $sheet = new GoogleWorksheetManager($worksheet);
    $header = $sheet->getHeader();
    $count = $sheet->getCount();
    for ( $i=0; $i<$count; $i++ ) {
        $row = $sheet->getRow($i);
        print_r($row);
        // $sheet->setRow($i, $row);

        // debug
        echo $i. ' ';
        if (PHP_SAPI !== 'cli') {
            ob_flush(); flush();
        }
    }
}

