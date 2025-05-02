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
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

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
   * Si se crea correctamente, se devolverá el objeto creado
   *
   * @param Request $req
   * @param Response $res
   */
  public static function POST(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

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

      // Crear
      $newFolder = PasswordsModel::create($result["data"]);

      $res->setData($newFolder);
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
      $data[FoldersModel::COL_OWNER_ID] = $session["user_id"];

      // Obtener password a actualizar
      $password = PasswordsModel::getPasswordByUserIdAndId($session["user_id"], $data[PasswordsModel::COL_ID] ?? "");

      if ($password === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      $result = PasswordSchema::partialValidate($data, $password);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Actualizar password
      $updatedFolder = PasswordsModel::update($result["data"], $data[PasswordsModel::COL_ID], $session["user_id"]);

      $res->setData($updatedFolder);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Elimina una `Password`
   *
   * @param Request $req
   * @param Response $res
   */
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
      $data[PasswordsModel::COL_OWNER_ID] = $session["user_id"];

      // Obtener password a eliminar
      $password = PasswordsModel::getPasswordByUserIdAndId($session["user_id"], $data[PasswordsModel::COL_ID] ?? "");

      if ($password === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      // Eliminar password
      PasswordsModel::delete($data[PasswordsModel::COL_ID]);

      $res->setData([ "password_deleted" => true ]);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
