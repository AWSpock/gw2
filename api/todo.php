<?php

header('Content-Type: application/json; charset=utf-8');

include_once("/var/www/gw2/_php/DataAccess/DataAccess.php");
$data = new DataAccess();

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // if (isset($vehicle_id)) {
        //     echo $data->vehicles($userAuth->user()->id())->getRecordById($vehicle_id)->toString();
        // } else {
            $recs = [];
            foreach ($data->todos()->getRecords() as $rec) {
                array_push($recs, json_decode($rec->toString()));
            }
            echo json_encode($recs);
        // }
        // echo json_encode(["test","value"]);
        break;
    case "POST":
        break;
    default:
        echo "Unknown Method";
        http_response_code(405);
        break;
}
