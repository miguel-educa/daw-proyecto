<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . PASSWORDS_MODEL_PATH;
require_once __DIR__ . "/../" . PASSWORD_SCHEMA_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;


/**
 * Controlador de la ruta `Passwords`
 */
class PasswordsController {
  /**
   * Recuperar `Passwords` del usuario autenticado
   *
   * @param Request $req
   * @param Response $res
   */
  public static function GET(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getActiveSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener data
      $user = PasswordsModel::getPasswordsByUserId($session["user_id"]);

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


  /**
   * Crea una `Password`. Si el cuerpo de la petición no contiene los parámetros
   * requeridos con el formato correcto, se mostrará un error
   *
   * Si se crea correctamente, se devolverá el objeto creado y se creará la Cookie de sesión
   *
   * @param Request $req
   * @param Response $res
   */
  public static function POST(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getActiveSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener datos
      $data = $req->getData();
      $data["owner_user_id"] = $session["user_id"];

      $result = PasswordSchema::validate($data);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Crear usuario y sesión
      $newFolder = PasswordsModel::create($result["data"]);

      $res->setData($newFolder);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
