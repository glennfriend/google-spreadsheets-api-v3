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

    show("done", true);
}

/**
 *
 */
function upgradeGoogleSheet()
{
    $token = GoogleApiHelper::getToken();
    if (!$token) {
        show('token error!', true);
        exit;
    }

    $worksheet = GoogleApiHelper::getWorksheet( conf('google.spreadsheets.book'), conf('google.spreadsheets.sheet') , $token );
    if (!$worksheet) {
        show('worksheet not found!', true);
        exit;
    }

    $sheet = new GoogleWorksheetManager($worksheet);
    $header = $sheet->getHeader();
    $count = $sheet->getCount();
    for ( $i=0; $i<$count; $i++ ) {

        $row = $sheet->getRow($i);
        $row = filterUnusedCode($row);
        show($row);

        // update sheet row
        if (getParam('exec')) {
            // 如果內容完全相同, 就不需要更新
            // 為了達到該效果, int 需要轉化為 string
            $originRow = $sheet->getRow($i);

            if ( md5(serialize($originRow)) === md5(serialize($row)) ) {
                echo "({$i}-same) ";
            }
            else {
                $result = writeSheet($row, $sheet, $i);
                if ($result) {
                    echo "{$i} ";
                }
                else {
                    echo "({$i}-udpate-fail) ";
                }
            }
        }

        // show message
        if (!isCli()) {
            ob_flush(); flush();
        }
    }

    show('');
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
        show($e->getMessage(), true);
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
