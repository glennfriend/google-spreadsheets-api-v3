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

// --------------------------------------------------------------------------------
// 
// --------------------------------------------------------------------------------

/**
 * 
 */
function perform()
{
    if ( phpversion() < '5.5' ) {
        show("PHP Version need >= 5.5");
        exit;
    }

    if (!getParam('exec')) {
        show('---- debug mode ---- (你必須要輸入參數 exec 才會真正執行)');
    }

    Log::record('start PHP '. phpversion() );
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

        // 無論如何都必須修改的值
        $row = filterUnusedCode($row);
        show($row);

        if (getParam('exec')) {
            writeSheet($row);
        }

        // debug
        show( $i . ' ' );
        if (PHP_SAPI !== 'cli') {
            ob_flush(); flush();
        }
    }
}

/**
 *  寫入
 */
function writeSheet($row)
{
    global $sheet;

    show('--------- go go go -------------');
    return;

    try {
        $sheet->setRow($i, $row);
    }
    catch ( Exception $e) {
        Log::record( $e->getMessage() );
        show($e->getMessage());
        exit;
    }
}

/**
 *  Clean invisible control characters and unused code points
 *
 *  \p{C} or \p{Other}: invisible control characters and unused code points.
 *      \p{Cc} or \p{Control}: an ASCII 0x00–0x1F or Latin-1 0x80–0x9F control character.
 *      \p{Cf} or \p{Format}: invisible formatting indicator.
 *      \p{Co} or \p{Private_Use}: any code point reserved for private use.
 *      \p{Cs} or \p{Surrogate}: one half of a surrogate pair in UTF-16 encoding.
 *      \p{Cn} or \p{Unassigned}: any code point to which no character has been assigned.
 *
 *  該程式可以清除 RIGHT-TO-LEFT MARK (200F)
 *
 *  @see http://www.regular-expressions.info/unicode.html
 *
 */
function filterUnusedCode($row)
{
    foreach ( $row as $index => $value ) {
        $row[$index] = preg_replace('/\p{C}+/u', "", $value );
    }
    return $row;
}
