<?php
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/" . REQUEST_METHOD_PATH;
require_once __DIR__ . "/" . REQUEST_PATH;
require_once __DIR__ . "/" . RESPONSE_PATH;
require_once __DIR__ . "/" . SESSION_CONTROLLER_PATH;


// Comprobar métodos permitidos
$allowedMethods = [
  RequestMethod::DELETE,
  RequestMethod::POST
];

$req = new Request($allowedMethods);
$req->checkRequestMethodAllowed();

$res = new Response();

// Rutas
if ($req->getMethod() === RequestMethod::DELETE) SessionController::DELETE($req, $res);
if ($req->getMethod() === RequestMethod::POST) SessionController::POST($req, $res);
