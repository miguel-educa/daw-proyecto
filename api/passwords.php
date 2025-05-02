<?php
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/" . REQUEST_METHOD_PATH;
require_once __DIR__ . "/" . REQUEST_PATH;
require_once __DIR__ . "/" . RESPONSE_PATH;
require_once __DIR__ . "/" . PASSWORDS_CONTROLLER_PATH;


// Comprobar métodos permitidos
$allowedMethods = [
  RequestMethod::DELETE,
  RequestMethod::GET,
  RequestMethod::PATCH,
  RequestMethod::POST,
];

$req = new Request($allowedMethods);
$req->checkRequestMethodAllowed();

$res = new Response();

// Rutas
if ($req->getMethod() === RequestMethod::DELETE) PasswordsController::DELETE($req, $res);
if ($req->getMethod() === RequestMethod::GET) PasswordsController::GET($req, $res);
if ($req->getMethod() === RequestMethod::PATCH) PasswordsController::PATCH($req, $res);
if ($req->getMethod() === RequestMethod::POST) PasswordsController::POST($req, $res);
