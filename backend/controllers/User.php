<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . USERS_MODEL_PATH;
require_once __DIR__ . "/../" . USER_SCHEMA_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;
require_once __DIR__ . "/../" . SESSION_DURATION_PATH;


/**
 * Controlador de la ruta `Users`
 */
class UserController {
  /**
   * Recuperar `User`
   * Es obligatorio uno de los siguientes `query params`:
   * - `username`: Retorna un `User` que coincida
   * - `name`: Retorna varios `User` que contengan el valor del filtro
   *
   * @param Request $req
   * @param Response $res
   */
  public static function GET(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $_COOKIE;
      $session = SessionsModel::getActiveSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Comprobar si se debe devolver información confidencial
      $filterValue = $req::getQueryParamValue("confidential_data");

      $confidentialData = $filterValue !== null ? $filterValue === "true" : false;

      // Obtener data
      $user = UsersModel::getUserById($session["user_id"], $confidentialData);

      // Comprobar que se hayan encontrado resultados
      if ($user === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      $res->setData($user);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
