<?php

include_once '../global/cors_post.php';

// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';
include_once '../global/utils.php';
include_once '../global/token_management.php';

// instantiate product object
include_once '../objects/login.php';
include_once '../objects/course.php';

$token = getBearerToken();
try {
    $decoded_token = decodeToken($token);
    
    if ($_FILES['data']['error'] <= 0) {
        $data = json_decode(file_get_contents("php://input"));
        $audio_path = $VAULT_PATH . "/" . $decoded_token->userUniqueId . "/" .  $COURSE_FOLDER_PREFIX . $_POST["course-id"] . "/" . $COURSE_DEMO_SUFFIX . "/" . $COURSE_AUDIO_PATH;
        if (
            move_uploaded_file($_FILES['data']['tmp_name'], $audio_path . "/" . $_FILES['data']['name'])
        ) {
            $response = array("message" => getMessage("genericSuccess"));
            http_response_code(200);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return true;
        }
    }


    $response = array("message" => getMessage("genericFailure"));
    http_response_code(401);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    $response = array("message" => getMessage("tokenInactive"));
    http_response_code(401);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
