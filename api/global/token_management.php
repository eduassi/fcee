<?php

include_once '../config/settings.php';

require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;



function generateToken($userUniqueId)
{
    global $LIFESPAN, $KEY, $BASE_URL;

    $timestamp = time();
    $expiration = $timestamp + $LIFESPAN;
    $payload = array(
        "iss" => $BASE_URL . "/api",
        "iat" => $timestamp,
        "exp" => $expiration,
        "scopes" => ["collaborator"],
        "userUniqueId" => $userUniqueId
    );
    $jwt = JWT::encode($payload, $KEY);
    return $jwt;
}

function generateAdminToken($userUniqueId)
{
    global $ADMIN_LIFESPAN, $KEY, $BASE_URL;

    $timestamp = time();
    $expiration = $timestamp + $ADMIN_LIFESPAN;
    $payload = array(
        "iss" => $BASE_URL . "/api",
        "iat" => $timestamp,
        "exp" => $expiration,
        "scopes" => ["admin"]
    );
    $jwt = JWT::encode($payload, $KEY);
    return $jwt;
}

function decodeToken($token)
{
    global $KEY;
    $jwt = JWT::decode($token, $KEY, array('HS256'));
    return $jwt;
}

function decodeAdminToken($token)
{
    global $KEY;
    $jwt = JWT::decode($token, $KEY, array('HS256'));
    if (!in_array("admin", $jwt->scopes)) {
        throw new Exception('Not a admin Token');
    }
    return $jwt;
}

function checkTokenExpiration($token)
{
    $exp = $token->exp;
    $current_time = time();
    $result = (bool) $exp > $current_time;
    if ($result) {
        return false;
    }
    return true;
}

function checkTokenElder($token)
{
    global $ELDER_CYCLE;
    $result = (bool) $token->exp - $ELDER_CYCLE > time();
    if ($result) {
        return false;
    }
    return true;
}


function checkTokenAuthenticity($token, $userId)
{
    return $token->userUniqueId == $userId;
}
