<?php

function initialize($basePath)
{
    error_reporting(E_ALL);
    ini_set('html_errors','On');
    ini_set('display_errors','On');

    require_once  $basePath . '/app/library/Config.php';
    Config::init( $basePath . '/app/config');

    if ( conf('public.base.path') !== $basePath ) {
       echo 'base path setting error!';
       echo "\n";
       exit;
    }

    date_default_timezone_set(conf('app.timezone'));


    require_once $basePath . '/app/library/Log.php';
    Log::init(   $basePath . '/var');

    require_once $basePath . '/vendor/autoload.php';

}

function conf($key)
{
    return Config::get($key);
}

function pr($data)
{
    print_r($data);
}

function show($data)
{
    echo $data;
    echo "\n";
}
