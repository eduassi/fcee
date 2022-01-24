<?php
include_once '../global/cors_post.php';
// get database connection
include_once '../config/database.php';
include_once '../global/utils.php';
include_once '../global/token_management.php';

// instantiate product object
include_once '../objects/login.php';

$database = new Database();
$db = $database->getConnection();
$table_abstraction = new Login($db);

$data = json_decode(file_get_contents("php://input"));

if ($data->username && $data->password) {
    $table_abstraction->username = $data->username;

    $waiting = $table_abstraction->request_account();
    $result = $waiting->fetchAll(PDO::FETCH_ASSOC);
    $loginSize = count($result);

    if ($waiting && $loginSize == 1) {
        $password_match = password_verify($data->password, $result[0]["password"]);

        if ($password_match) {
            $userUniqueId = $result[0]["unique_id"];

            $new_folder_path = $VAULT_PATH . "/" . $userUniqueId;
            $new_profile_folder_path = $new_folder_path . "/profile";

            if (!file_exists($new_folder_path)) {
                mkdir($new_folder_path);
            }
            if (!file_exists($new_profile_folder_path)) {
                mkdir($new_profile_folder_path);
            }


            $token = generateToken($userUniqueId);
            $response = array(
                "message" => getMessage("successfulLogin"),
                "token" => $token,
            );
            http_response_code(200);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            $response["message"] = getMessage("wrongLogin");
            http_response_code(401);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else if ($loginSize == 0) {
        $response["message"] = getMessage("wrongLogin");
        http_response_code(401);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {
        $response["message"] = getMessage("unexpectedError");
        http_response_code(503);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} else {
    $response["message"] = getMessage("missingParameters");
    http_response_code(503);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
