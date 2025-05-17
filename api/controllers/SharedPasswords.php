<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . PASSWORDS_MODEL_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;


/**
 * Controlador de la ruta `Passwords`
 */
class SharedPasswordsController {
  /**
   * Recuperar `Passwords` del usuario autenticado
   *
   * @param Request $req
   * @param Response $res
   */
  public static function GET(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener data
      $result = $req->getQueryParamValue("shared_password_id") !== null
        ? PasswordsModel::getSharedPasswordUsersByUserIdAndId($session["user_id"], $req->getQueryParamValue("shared_password_id"))
       : PasswordsModel::getSharedPasswordsByUserId($session["user_id"]);

      // Comprobar que se hayan encontrado resultados
      if ($result === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      $res->setData($result);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Modifica una `Password`. Si el cuerpo de la petición no contiene los parámetros
   * requeridos con el formato correcto, se mostrará un error
   *
   * Si se modifica correctamente, se devolverá el objeto modificado
   *
   * @param Request $req
   * @param Response $res
   */
  public static function PATCH(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener datos
      $data = $req->getData();
      $userOwnerId = $session["user_id"];

      if (!isset($data["shared_password_id"]) || !isset($data["shared_users"]) || count($data["shared_users"]) === 0) {
        $res->addError("No se especificó ningún usuario compartido");
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
        return;
      }

      // Obtener usuarios compartidos
      $users = PasswordsModel::getSharedPasswordUsersByUserIdAndId($userOwnerId, $data["shared_password_id"] ?? "", true);

      $addUsers = [];
      $deleteUsers = [];

      foreach ($users as $u) {
        $found = false;
        foreach ($data["shared_users"] as $u2) {
          if ($u["shared_user_username"] === $u2["shared_user_username"]) {
            $found = true;
            break;
          }

          $found = false;
        }

        if (!$found) $deleteUsers[] = $u["shared_user_id"];
      }

      foreach ($data["shared_users"] as $u) {
        $found = false;
        foreach ($users as $u2) {
          if ($u["shared_user_username"] === $u2["shared_user_username"]) {
            $found = true;
            break;
          }

          $found = false;
        }

        if (!$found) $addUsers[] = $u["shared_user_username"];
      }

      PasswordsModel::updateSharedUsers($data["shared_password_id"], $addUsers, $deleteUsers);

      $res->setData([ "updated" => true ]);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
