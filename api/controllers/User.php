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
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

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

      unset($user[UsersModel::COL_TOTP_2FA_SECRET]);
      $res->setData($user);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Modifica una `User`. Si el cuerpo de la petición no contiene los parámetros
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

      // Obtener password a actualizar
      $user = UsersModel::getUserById($session["user_id"], confidentialData: true);

      if ($user === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      $result = UserSchema::partialValidate($data, $user);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Actualizar password
      $updatedUser = UsersModel::update($result["data"], $session["user_id"]);

      $res->setData($updatedUser);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }

  public static function DELETE(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener datos
      $data = $req->getData();

      $deleteTypes = ["account", "vault", "shared-vault"];

      if (!isset($data["type"]) || !in_array($data["type"], $deleteTypes)) {
        $res->addError("El tipo de eliminación no es válido");
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      if ($data["type"] === "account") {
        UsersModel::delete($session["user_id"]);
        $res->deleteCookie("session_token");
      }

      if ($data["type"] === "vault") {
        UsersModel::deleteVault($session["user_id"]);
      }

      if ($data["type"] === "shared-vault") {
        UsersModel::deleteSharedVault($session["user_id"]);
      }

      $res->setData(["deleted" => true]);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
