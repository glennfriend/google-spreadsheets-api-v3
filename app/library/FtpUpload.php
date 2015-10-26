<?php

class FtpUpload
{
    /**
     *
     */
    protected $connect;

    /**
     *
     */
    public function __construct($server, $user, $password)
    {
        $this->connect = ftp_connect($server);
        $result = ftp_login($this->connect, $user, $password);
        if (!$result) {
            throw new Exception('FTP Create Connect Error!');
        }
    }

    /**
     *
     */
    public function close()
    {
        ftp_close($this->connect);
    }

    /**
     *
     */
    public function upload($uploadFile, $filename)
    {
        if (!ftp_put($this->connect, $filename, $uploadFile, FTP_ASCII)) {
            return false;
        }
        return true;
    }

}