<?php
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

class GoogleApiHelper
{
    /**
     *  @return token or false
     */
    public static function getToken()
    {
        $key = file_get_contents( conf('public.base.path') .'/'. conf('google.client_key_file') );
        $cred = new Google_Auth_AssertionCredentials(
            conf('google.client_email'),
            array('https://spreadsheets.google.com/feeds'),
            $key
        );

        $client = new Google_Client();
        $client->setAssertionCredentials($cred);

        if (!$client->getAuth()->isAccessTokenExpired()) {
            return false;
        }
        else {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }

        $service_token = json_decode($client->getAccessToken());
        return $service_token->access_token;
    }

    /**
     *  @return worksheet or false
     */
    public static function getWorksheet( $book, $page, $token )
    {
        $serviceRequest = new DefaultServiceRequest($token);
        ServiceRequestFactory::setInstance($serviceRequest);

        $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
        $spreadsheetFeed = $spreadsheetService->getSpreadsheets();

        $spreadsheet = $spreadsheetFeed->getByTitle($book);
        if ( !$spreadsheet ) {
            return false;
        }

        $worksheets = $spreadsheet->getWorksheets();
        if ( !$worksheets ) {
            return false;
        }

        return $worksheets->getByTitle($page);
    }

}
