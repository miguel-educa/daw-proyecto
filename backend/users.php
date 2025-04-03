<?php
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/" . REQUEST_METHOD_PATH;
require_once __DIR__ . "/" . REQUEST_PATH;
require_once __DIR__ . "/" . RESPONSE_PATH;
require_once __DIR__ . "/" . USERS_CONTROLLER_PATH;


// Comprobar métodos permitidos
$allowedMethods = [
  RequestMethod::GET,
  RequestMethod::POST,
];

$req = new Request($allowedMethods);
$req->checkRequestMethodAllowed();

$res = new Response();

// Rutas
if ($req->getMethod() === RequestMethod::GET) UsersController::GET($req, $res);
if ($req->getMethod() === RequestMethod::POST) UsersController::POST($req, $res);
