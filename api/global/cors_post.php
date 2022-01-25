<?php
include_once '../config/allowed_domains.php';

header("Content-Type: application/json; charset=UTF-8");
if (isset($_SERVER["HTTP_ORIGIN"]) === true) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if (in_array($origin, $ALLOWED_DOMAINS, true) === true) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
    }
    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        header('Access-Control-Allow-Methods: OPTIONS');
        exit;
    }
}
