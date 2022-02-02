<?php
date_default_timezone_set('America/Sao_Paulo');

// SYSTEM
$TESTING_MODE = true;

if($_SERVER['HTTP_HOST'] == "localhost"){
    $BASE_URL = "https://localhost/projects/FCEE/";
}else{
    $BASE_URL = "https://inscricao.fcee-sc.net.br/";
}
$LANGUAGE = "pt";

// AUTHENTIFICATION
$PASSWORD_MIN_SIZE = 8;

// TOKEN MANAGEMENT
$KEY = "VeEgu934[O|QZ+=]QA?#ksWj#Q@3jY4An0#Q5hs#=sjgW&yxft+Z>uyv*jqBm9PM3c2Z4NjEOlyTrAKPpCVD9LJiUmT8#hOTDKT_p#Xac{4GOXiZm9PM3c2Z4NjEOlyTrAKPpCVD9LJiUmT8";
$ADMIN_LIFESPAN = 1800;
$LIFESPAN = 180000;
$ELDER_CYCLE = 300;

$PICTURE_DOCUMENT_NAME = "documento-foto";
$COMPROVATION_DOCUMENT_NAME = "documento-comprovacao-vinculo";

/// PATHS
$LOCAL_URL = "../..";
$FILES_PATH = $BASE_URL . "documents/";