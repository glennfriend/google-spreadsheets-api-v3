<?php
#!/usr/bin/php -q

if (PHP_SAPI !== 'cli') {
    if ( '192.168.' !== substr($_SERVER['REMOTE_ADDR'],0,8)  )
    {
        exit;
    }
    echo "<pre>\n";
}

error_reporting(E_ALL);
ini_set('html_errors','On');
ini_set('display_errors','On');

require_once 'config/config.php';
date_default_timezone_set(APPLICATION_TIMEZONE);

require_once 'vendor/autoload.php';
require_once 'library/Log.php';
require_once 'library/CsvManager.php';
require_once 'library/ArrayIndex.php';
require_once 'library/GoogleWorksheetManager.php';
require_once 'helper/GoogleApiHelper.php';
require_once 'helper/GoogleSheetdownloadHelper.php';
require_once 'helper/MailHelper.php';

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

    $worksheet = GoogleApiHelper::getWorksheet( APPLICATION_GOOGLE_SPREADSHEETS_BOOK, APPLICATION_GOOGLE_SPREADSHEETS_SHEET, $token );
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
        echo $i. ' '; ob_flush(); flush();
    }
}

