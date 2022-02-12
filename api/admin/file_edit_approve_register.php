<?php
// required headers

include_once '../global/cors_post.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../objects/register.php';

include_once '../global/utils.php';
include_once '../global/token_management.php';
include_once '../global/mail.php';

$token = getBearerToken();
try {
    $decoded_token = decodeToken($token);
    $data = json_decode(file_get_contents("php://input"));

    $database = new Database();
    $db = $database->getConnection();


    $maximum_size = 25000000; // 25MB
    $valid_extensions = array('jpeg', 'jpg', 'png', 'pdf');

    if (array_key_exists("documentoFoto", $_FILES) && array_key_exists("documentoVinculo", $_FILES) && $_POST["cpf"] && $_POST["id"] && $_POST["cpf"] && $_POST["id"]) {
        $doc_foto = $_FILES["documentoFoto"];
        $doc_foto_ext = pathinfo($doc_foto["name"], PATHINFO_EXTENSION);
        $doc_vinculo = $_FILES["documentoVinculo"];
        $doc_vinculo_ext = pathinfo($doc_vinculo["name"], PATHINFO_EXTENSION);

        if (
            in_array($doc_foto_ext, $valid_extensions) &&
            in_array($doc_vinculo_ext, $valid_extensions) &&
            $doc_foto["size"] < $maximum_size &&
            $doc_vinculo["size"] < $maximum_size
        ) {

            $table_abstraction = new Register($db);
            $table_abstraction->id = $_POST["id"];
            $table_abstraction->cpf = $_POST["cpf"];
            $table_abstraction->documentoFoto = $doc_foto_ext;
            $table_abstraction->documentoVinculo = $doc_vinculo_ext;

            $upload_path = '../../documents/' . $table_abstraction->getCPF() . '/';

            $db->beginTransaction();

            remove_dir_recursively($upload_path);
            mkdir($upload_path, 0777, true);

            $query_result = $table_abstraction->update_files_approve_register();

            if (
                $query_result &&
                move_uploaded_file($doc_foto['tmp_name'], $upload_path . $PICTURE_DOCUMENT_NAME . "." . $doc_foto_ext) &&
                move_uploaded_file($doc_vinculo['tmp_name'], $upload_path . $COMPROVATION_DOCUMENT_NAME . "." . $doc_vinculo_ext)

            ) {
                $db->commit();
                send_approval_email($table_abstraction->getName(), $table_abstraction->getEmail());

                $response = array("message" => getMessage("genericSuccess"));
                http_response_code(201);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            } else {
                $response = array("message" => getMessage("genericFailure"));
                http_response_code(503);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $response = array("message" => "Arquivos com extensão não suportada.");
            http_response_code(503);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else if (array_key_exists("documentoFoto", $_FILES) && $_POST["cpf"] && $_POST["id"]) {
        $doc_foto = $_FILES["documentoFoto"];
        $doc_foto_ext = pathinfo($doc_foto["name"], PATHINFO_EXTENSION);
        if (
            in_array($doc_foto_ext, $valid_extensions) &&
            $doc_foto["size"] < $maximum_size
        ) {

            $table_abstraction = new Register($db);
            $table_abstraction->id = $_POST["id"];
            $table_abstraction->cpf = $_POST["cpf"];
            $table_abstraction->documentoFoto = $doc_foto_ext;
            $table_abstraction->documentoVinculo = $_POST["documentoVinculoExt"];

            $upload_path = '../../documents/' . $table_abstraction->getCPF() . '/';
            $old_file_path = $upload_path . 'documento-foto.' . $_POST["documentoFotoExt"];

            $db->beginTransaction();

            $query_result = $table_abstraction->update_files_approve_register();

            unlink($old_file_path);

            if (
                $query_result &&
                move_uploaded_file($doc_foto['tmp_name'], $upload_path . $PICTURE_DOCUMENT_NAME . "." . $doc_foto_ext)
            ) {
                $db->commit();
                send_approval_email($table_abstraction->getName(), $table_abstraction->getEmail());

                $response = array("message" => getMessage("genericSuccess"));
                http_response_code(201);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            } else {
                $response = array("message" => getMessage("genericFailure"));
                http_response_code(503);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $response = array("message" => "Arquivos com extensão não suportada.");
            http_response_code(503);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else if (array_key_exists("documentoVinculo", $_FILES) && $_POST["cpf"] && $_POST["id"]) {
        $doc_vinculo = $_FILES["documentoVinculo"];
        $doc_vinculo_ext = pathinfo($doc_vinculo["name"], PATHINFO_EXTENSION);
        if (
            in_array($doc_vinculo_ext, $valid_extensions) &&
            $doc_vinculo["size"] < $maximum_size
        ) {

            $table_abstraction = new Register($db);
            $table_abstraction->id = $_POST["id"];
            $table_abstraction->cpf = $_POST["cpf"];
            $table_abstraction->documentoFoto = $_POST["documentoFotoExt"];
            $table_abstraction->documentoVinculo = $doc_vinculo_ext;

            $upload_path = '../../documents/' . $table_abstraction->getCPF() . '/';
            $old_file_path = $upload_path . 'documento-comprovacao-vinculo.' . $_POST["documentoVinculoExt"];

            $db->beginTransaction();

            $query_result = $table_abstraction->update_files_approve_register();

            unlink($old_file_path);

            if (
                $query_result &&
                move_uploaded_file($doc_vinculo['tmp_name'], $upload_path . $COMPROVATION_DOCUMENT_NAME . "." . $doc_vinculo_ext)
            ) {
                $db->commit();
                send_approval_email($table_abstraction->getName(), $table_abstraction->getEmail());

                $response = array("message" => getMessage("genericSuccess"));
                http_response_code(201);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            } else {
                $response = array("message" => getMessage("genericFailure"));
                http_response_code(503);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $response = array("message" => "Arquivos com extensão não suportada.");
            http_response_code(503);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $response = array("message" => "Faltam arquivos.");
        http_response_code(503);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    echo $e;
    $response = array("message" => getMessage("tokenInactive"));
    http_response_code(401);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
