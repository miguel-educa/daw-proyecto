<?php
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/" . REQUEST_METHOD_PATH;
require_once __DIR__ . "/" . REQUEST_PATH;
require_once __DIR__ . "/" . RESPONSE_PATH;
require_once __DIR__ . "/" . USER_CONTROLLER_PATH;


// Comprobar métodos permitidos
$allowedMethods = [
  RequestMethod::DELETE,
  RequestMethod::GET,
  RequestMethod::PATCH,
];

$req = new Request($allowedMethods);
$req->checkRequestMethodAllowed();

$res = new Response();

// Rutas
if ($req->getMethod() === RequestMethod::DELETE) UserController::DELETE($req, $res);
if ($req->getMethod() === RequestMethod::GET) UserController::GET($req, $res);
if ($req->getMethod() === RequestMethod::PATCH) UserController::PATCH($req, $res);
