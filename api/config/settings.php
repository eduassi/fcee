<?php
date_default_timezone_set('America/Sao_Paulo');

// SYSTEM
$TESTING_MODE = true;

if($_SERVER['HTTP_HOST'] == "localhost"){
    $BASE_URL = "https://localhost/projects/RiconBuilder/";
}else{
    $BASE_URL = "https://ricon.generalwebsolutions.com.br/";
}
// $FINISH_REGISTER_URL = "https://ricon.generalwebsolutions.com.br/#finishRegister";
$LANGUAGE = "pt";

// AUTHENTIFICATION
$PASSWORD_MIN_SIZE = 8;

// TOKEN MANAGEMENT
$KEY = "VeEgu934[O|QZ+=]QA?#ksWj#Q@3jY4An0#Q5hs#=sjgW&yxft+Z>uyv*jqBm9PM3c2Z4NjEOlyTrAKPpCVD9LJiUmT8#hOTDKT_p#Xac{4GOXiZm9PM3c2Z4NjEOlyTrAKPpCVD9LJiUmT8";
$ADMIN_LIFESPAN = 1800;
$LIFESPAN = 180000;
$ELDER_CYCLE = 300;

// IMAGES SETTINGS

/// ALLOWED SIZE
$IMAGE_MAX_SIZE = 1024;
$IMAGE_THUMB_SIZES = [800, 480, 360, 280];

/// QUALITY SETTINGS
$QUALITY = 85;
$DPI = 72;

/// PATHS
$LOCAL_URL = "../..";
$VAULT_PATH = "../../vault";
$COURSE_FOLDER_PREFIX = "course-";
$COURSE_FULL_PATH = $BASE_URL . "UserPortal/vault";
$COURSE_DEMO_SUFFIX = "/demo";
$COURSE_AUDIO_PATH = "/assets/audios";
$COURSE_IMAGE_PATH = "/assets/images/content";
$COURSE_PAGES_PATH = "/pages";
$COURSE_ZIP_SUFIX_PATH = "/package.zip";

$TEMPLATES_PATH = "../../templates";
$TEMPLATES_FULL_PATH = $BASE_URL . "UserPortal/templates/";
$TEMPLATES_MISC_SUFFIX = "/misc";
$TEMPLATES_DEMO_SUFFIX = "/demo";

$PREVIEW_IMAGE_FILE = "/preview.jpg";
$TEMPLATE_STYLE_FILE = "/theme_style.css";
$TEMPLATE_SCRIPT_FILE = "/theme_script.js";

$COVER_IMAGE_FILE = "/cover-image.png";
$LOGO_IMAGE = "/logo-image.png";
$MIN_LOGO_IMAGE = "/min-logo-image.png";

$PLACEHOLDER_COVER_FILE = "assets/images/content/placeholder-image.jpg";
$PLACEHOLDER_LOGO_FILE = "assets/images/content/placeholder-logo-image.png";
$PLACEHOLDER_MIN_LOGO_FILE = "assets/images/content/placeholder-min-logo-image.png";

$IDENTITY_WHITE_LIST = ["cover-image", "logo-image", "min-logo-image"];