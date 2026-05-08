<?php

if (!isset($_GET['api_key']) || empty($_GET['api_key'])) {
    echo "Missing API Key";
    http_response_code(400);
    exit;
}

$api_key = $_GET['api_key'];

header('Content-Type: application/json; charset=utf-8');

include_once("/var/www/gw2/_php/DataAccess/DataAccess.php");
$data = new DataAccess();

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        echo json_encode($data->todos()->getCompleted($api_key));
        break;
    case "POST":
        if (!isset($_POST) || empty($_POST['identifier'])) {
            echo "Missing Identifier";
            http_response_code(400);
            exit;
        }
        if ($data->todos()->toggle($api_key, $_POST['identifier'])) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        break;
    default:
        echo "Unknown Method";
        http_response_code(405);
        break;
}
