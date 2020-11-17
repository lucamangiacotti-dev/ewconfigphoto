<?php

if (!defined('_PS_VERSION_'))
    exit;


define("EW_MODULE_DIR", __DIR__);

require_once __DIR__ . "/classes/SessionManager.php";
require_once __DIR__ . "/classes/ProductConfigurator.php";
require_once __DIR__ . "/classes/JoinCombinations.php";

require_once __DIR__ . "/hooks/DisplayAdminProductsExtra.php";
require_once __DIR__ . "/hooks/DisplayProductAdditionalInfo.php";
require_once __DIR__ . "/hooks/DisplayAdminProductsCombinationBottom.php";

require_once __DIR__ . "/hooks/ActionProductSave.php";
require_once __DIR__ . "/hooks/ActionFrontControllerSetMedia.php";