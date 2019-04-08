<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

ini_set('session.cookie_domain', "vulkan-mc.fr");

defined("DS") || define("DS", DIRECTORY_SEPARATOR);
define("APPDIR", dirname(__DIR__) . "/revocms/app/");
define("SYSTEMDIR", dirname(__DIR__) . "/revocms/system/");
define("PUBLICDIR", dirname(__FILE__) . "/");
define("ROOTDIR", dirname(__DIR__) . "/revocms/");
define("TMPDIR", ROOTDIR . "tmp/");
define("DIR", dirname(__DIR__) . "/");
define("ENVIRONMENT", "dev");
define("BASE_URL", "/");

require SYSTEMDIR . "Load/Autoload.php";
require SYSTEMDIR . "Load/Loader.php";