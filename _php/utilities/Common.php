<?php

// function ReturnCurrentPath()
// {
//     $path = "";
//     $req = $_SERVER['REDIRECT_URL'];
//     switch ($req) {
//         case "/":
//             $path = "/index";
//             break;
//         default:
//             $path = $req;
//             break;
//     }
//     return $path;
// }

function IncludeCSS($file)
{
    if (file_exists("/var/www/mileage" . $file)) {
        $v = filemtime("/var/www/mileage" . $file);
        echo "<link rel='stylesheet' type='text/css' href='//mileage.spockfamily.net" . $file . "?v=" . $v . "' />\n";
    }
}
function IncludeJS($file)
{
    if (file_exists("/var/www/mileage" . $file)) {
        $v = filemtime("/var/www/mileage" . $file);
        echo "<script src='//mileage.spockfamily.net" . $file . "?v=" . $v . "'></script>\n";
    }
}

function ReturnUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

function FormatMoney($val)
{
    $formatter_us = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    return $formatter_us->formatCurrency($val, 'USD') . PHP_EOL;
}
