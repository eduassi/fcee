<?php
include_once '../config/settings.php';
include_once 'messages.php';


function generateRandom($entropyA, $entropyB, $size)
{
    $uniqueIdGenerated = "";
    do {
        $uniqueIdGenerated = rand() . str_shuffle(str_shuffle($entropyA) . rand()) . str_shuffle(str_shuffle($entropyB) . rand()) . rand();
        $uniqueIdGenerated = str_shuffle($uniqueIdGenerated);
        $uniqueIdGenerated = uniqid($uniqueIdGenerated, true);
        $uniqueIdGenerated = str_replace(".", "", $uniqueIdGenerated);
        $uniqueIdGenerated = substr($uniqueIdGenerated, 0, $size);
    } while (strlen($uniqueIdGenerated) != $size);
    return $uniqueIdGenerated;
}

function tinyIntCast($value)
{
    if (is_numeric($value)) {
        $value = (int) $value;
        $result = ($value == 1 || $value == 0) ? $value : "NULL";
    } else {
        $result = "NULL";
    }

    return $result;
}

function numberOrNullCast($value)
{
    $result = is_numeric($value) ? $value : "NULL";
    return $result;
}

function checkCustomType($type, $value)
{
    global $MESSAGES, $LANGUAGE, $PASSWORD_MIN_SIZE;
    $response = array(
        "result" => false,
        "message" => "Unexpected Error",
    );

    switch ($type) {
        case "cellphone":
            if (empty($value)) {
                $response["message"] = $MESSAGES[$LANGUAGE]["cellPhoneEmpty"];
            } else if (!is_numeric($value) || strlen($value) != 11) {
                $response["message"] = $MESSAGES[$LANGUAGE]["cellPhoneWrongFormat"];
            } else {
                $response["result"] = true;
                $response["message"] = "";
            }
            break;
        case "password":
            if (strlen($value) < $PASSWORD_MIN_SIZE) {
                $response["message"] = $MESSAGES[$LANGUAGE]["passwordShort"];
            } else if (!ctype_alnum($value)) {
                $response["message"] = $MESSAGES[$LANGUAGE]["passwordWrongFormat"];
            } else {
                $response["result"] = true;
                $response["message"] = "";
            }
            break;
    }
    return $response;
}

function checkConsistence($data)
{
    $response = array(
        "result" => true,
        "message" => array(),
    );
    foreach ($data as $key => $element) {
        $checkResult = checkCustomType($element["type"], $element["value"]);
        if (!$checkResult["result"]) {
            $response["result"] = false;
            array_push($response["message"], $checkResult["message"]);
        }
    }

    return $response;
}

function getMessage($msg)
{
    global $MESSAGES, $LANGUAGE;
    return $MESSAGES[$LANGUAGE][$msg];
}

function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } else if (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

function getBearerToken()
{
    $headers = getAuthorizationHeader();
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

function set_object_vars_to_query($object, $vars)
{
    $query_params = array();
    $query_values = array();
    $query_pair = array();
    $vars = (array) $vars;
    $has = $object->get_unrestricted_vars();
    foreach ($has as $name => $oldValue) {
        if (isset($vars[$name])) {
            $object->$name = $vars[$name];
            $bind_name = "=:" . $name;
            $pair = $name . $bind_name;
            array_push($query_params, $name);
            array_push($query_values, $bind_name);
            array_push($query_pair, $pair);
        }
    }
    $query_pieces = array(
        "parameters" => $query_params,
        "values" => $query_values,
        "pair" => $query_pair,
    );
    return $query_pieces;
}

function create_dir_if_not_exists($PATH)
{
    if (!is_dir($PATH)) {
        mkdir($PATH);
        return true;
    }
    return false;
}

function remove_dir_recursively($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                    remove_dir_recursively($dir . DIRECTORY_SEPARATOR . $object);
                else {
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        rmdir($dir);
    }
}

function copy_dir_recursively(
    string $sourceDirectory,
    string $destinationDirectory,
    string $childFolder = ''
): void {
    $directory = opendir($sourceDirectory);

    if (is_dir($destinationDirectory) === false) {
        mkdir($destinationDirectory);
    }

    if ($childFolder !== '') {
        if (is_dir("$destinationDirectory/$childFolder") === false) {
            mkdir("$destinationDirectory/$childFolder");
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                copy_dir_recursively("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            } else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            }
        }

        closedir($directory);

        return;
    }

    while (($file = readdir($directory)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        if (is_dir("$sourceDirectory/$file") === true) {
            copy_dir_recursively("$sourceDirectory/$file", "$destinationDirectory/$file");
        } else {
            copy("$sourceDirectory/$file", "$destinationDirectory/$file");
        }
    }

    closedir($directory);
}

function dir_is_empty($dirname)
{
    if (!is_dir($dirname)) return false;
    foreach (scandir($dirname) as $file) {
        if (!in_array($file, array('.', '..', '.svn', '.git'))) return false;
    }
    return true;
}


function zip_folder_and_download($source_folder, $destination_folder)
{
    global $COURSE_ZIP_SUFIX_PATH;

    $rootPath = realpath($source_folder);

    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($destination_folder . $COURSE_ZIP_SUFIX_PATH, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
}

function folder_size($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folder_size($each);
    }

    return $size;
}

function folder_full_log($dir)
{
    $total_size = 0;
    $full_log = [];
    $dirs = scandir($dir);
    array_splice($dirs, 0, 2);
    foreach ($dirs as $sub_root) {
        $sub_dir = $dir . "/" . $sub_root;
        $sub_size = intval(folder_size($sub_dir) / 1000000);
        $full_log[$sub_root] = $sub_size;
        $total_size += $sub_size;
    }
    $full_log["total"] = $total_size;
    return $full_log;
}



function createImage($data, $extension, $new_filename, $destination_path)
{
    $new_filename = $new_filename . "." . $extension;
    $image_full_path = $destination_path . "/" . $new_filename;
    $decoded_image = explode(',', $data)[1];
    $decoded_image = base64_decode($decoded_image);
    file_put_contents($image_full_path, $decoded_image);
    return $new_filename;
}
