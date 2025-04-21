<?php
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/" . REQUEST_METHOD_PATH;
require_once __DIR__ . "/" . REQUEST_PATH;
require_once __DIR__ . "/" . RESPONSE_PATH;
require_once __DIR__ . "/" . ACCOUNT_RECOVERY_CONTROLLER_PATH;


// Comprobar métodos permitidos
$allowedMethods = [ RequestMethod::POST ];

$req = new Request($allowedMethods);
$req->checkRequestMethodAllowed();

$res = new Response();

// Métodos
if ($req->getMethod() === RequestMethod::POST) AccountRecoveryController::POST($req, $res);
