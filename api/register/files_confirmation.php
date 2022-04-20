<?php
// required headers

include_once '../global/cors_post.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../objects/register.php';

include_once '../global/utils.php';
include_once '../global/mail.php';

// get posted data
// new_register

$data = json_decode(file_get_contents("php://input"));

$database = new Database();
$db = $database->getConnection();


$maximum_size = 25000000; // 25MB
$valid_extensions = array('jpeg', 'jpg', 'png', 'pdf');



if (array_key_exists("doc-com-foto", $_FILES) && array_key_exists("doc-vinculo", $_FILES)) {
    $doc_foto = $_FILES["doc-com-foto"];
    $doc_foto_ext = pathinfo($doc_foto["name"], PATHINFO_EXTENSION);
    $doc_vinculo = $_FILES["doc-vinculo"];
    $doc_vinculo_ext = pathinfo($doc_vinculo["name"], PATHINFO_EXTENSION);

    if (
        in_array($doc_foto_ext, $valid_extensions) &&
        in_array($doc_vinculo_ext, $valid_extensions) &&
        $doc_foto["size"] < $maximum_size &&
        $doc_vinculo["size"] < $maximum_size &&
        $_POST["cpf"]
    ) {

        $table_abstraction = new Register($db);
        $table_abstraction->cpf = $_POST["cpf"];
        $table_abstraction->documentoFoto = $doc_foto_ext;
        $table_abstraction->documentoVinculo = $doc_vinculo_ext;
        $table_CPF = $table_abstraction->getCPF();
        $success_actions = false;
        $confirmation_file_path = '../../documents/' . $table_CPF . '/';



        $db->beginTransaction();
        echo var_dump($table_abstraction->cpf_exists()->fetchAll(PDO::FETCH_ASSOC));
        if($table_abstraction->cpf_exists()->fetchAll(PDO::FETCH_ASSOC)){
            if (
                $table_CPF &&
                $confirmation_file_path != '../../documents//' &&
                $confirmation_file_path != '../../documents/' &&
                $confirmation_file_path != '../../documents'
            ) {
                if (
                    (is_dir($confirmation_file_path) && dir_is_empty($confirmation_file_path)) ||
                    !file_exists($confirmation_file_path)
                ) {
    
                    $query_result = $table_abstraction->refreshExtensions();
                    if ($query_result) {
                        
                        if (!file_exists($confirmation_file_path)) {
                            mkdir($confirmation_file_path, 0777, true);
                        }
    
                        if (
                            move_uploaded_file($doc_foto['tmp_name'], $confirmation_file_path . $PICTURE_DOCUMENT_NAME . "." . $doc_foto_ext) &&
                            move_uploaded_file($doc_vinculo['tmp_name'], $confirmation_file_path . $COMPROVATION_DOCUMENT_NAME . "." . $doc_vinculo_ext)
                        ) {
                            $success_actions = true;
                        }
                    }
                }
            }
        }


        if ($success_actions) {
            $db->commit();
        } else {
            $db->rollBack();
        }

        $response = array("message" => getMessage("genericSuccess"));
        http_response_code(201);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
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
