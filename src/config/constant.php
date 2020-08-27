<?php 
error_reporting(-1); // for dev
ini_set('display_errors', 1); // for dev
ini_set('display_errors', 0); // for product
// for dev CF_UPLOAD_LOCAL_GAME = local
// for product CF_UPLOAD_LOCAL_GAME = cloud
// define('CF_UPLOAD_LOCAL_GAME', 'cloud');
define('CF_UPLOAD_LOCAL_GAME', 'local');

//define
define('TOKEN_KEY', 'wD6jCXn25Z39M2Pz');
define('TOKEN_EXPIRE_TIME', '300');
define('PROJECT_KEY_NAME', 'game-boc-phot-cong-so');
// define('PATH_PROJECT', '/game-boc-phot-cong-so/'); // for product
define('PATH_PROJECT', '/');// for local dev
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('SESSION_START_TIME_NAME', 'start_time');
define('SESSION_START_TIME_EXPIRE', 43200);//12hour
define('URL_ROOT_DOMAIN', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" . "://$_SERVER[HTTP_HOST]" : "http" . "://$_SERVER[HTTP_HOST]");
// define('URL_ROOT_PROJECT', URL_ROOT_DOMAIN . '/game-boc-phot-cong-so/'); // for product
define('URL_ROOT_PROJECT', URL_ROOT_DOMAIN . '/'); // for local dev
define('UPLOAD_URL', URL_ROOT_PROJECT . 'image/download/');
define('UPLOAD_DIR', '../../image/download/');

//cloud image
define('CLOUD_IMG_DOMAIN', 'https://adi.admicro.vn/adt/gstudio/');
define('CLOUD_IMG_REAL_PATH', '/adt/gstudio/');