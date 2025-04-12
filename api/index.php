<?php
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/" . RESPONSE_PATH;
require_once __DIR__ . "/" . HTTP_CODE_PATH;

$res = new Response();

$res->showResponseAndExit(HttpCode::OK);
