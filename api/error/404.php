<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../tools/Response.php";
require_once __DIR__ . "/../tools/HttpCode.php";

$res = new Response();
$res->addError("La ruta solicitada no existe");
$res->showResponseAndExit(HttpCode::NOT_FOUND);
