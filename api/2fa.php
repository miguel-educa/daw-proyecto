<?php
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/" . REQUEST_METHOD_PATH;
require_once __DIR__ . "/" . REQUEST_PATH;
require_once __DIR__ . "/" . RESPONSE_PATH;
require_once __DIR__ . "/" . TWO_FA_CONTROLLER_PATH;

// Comprobar métodos permitidos
$allowedMethods = [
  RequestMethod::POST,
  RequestMethod::DELETE
];

$req = new Request($allowedMethods);
$req->checkRequestMethodAllowed();

$res = new Response();

// Rutas
if ($req->getMethod() === RequestMethod::POST) twoFAController::CREATE($req, $res);
if ($req->getMethod() === RequestMethod::DELETE) twoFAController::DELETE($req, $res);
