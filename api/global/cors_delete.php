<?php
include_once '../config/allowed_domains.php';

header("Content-Type: application/json; charset=UTF-8");
if (isset($_SERVER["HTTP_ORIGIN"]) === true) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if (in_array($origin, $ALLOWED_DOMAINS, true) === true) {
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json; charset=UTF-8");
        header('Access-Control-Allow-Methods: DELETE');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }
    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        header('Access-Control-Allow-Methods: OPTIONS');
        exit;
    }
}
