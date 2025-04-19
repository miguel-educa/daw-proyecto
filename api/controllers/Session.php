<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . SESSION_SCHEMA_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;
require_once __DIR__ . "/../" . USERS_MODEL_PATH;
require_once __DIR__ . "/../" . GOOGLE_AUTHENTICATOR_PATH;


/**
 * Controlador de la ruta `Session`
 */
class SessionController {
  /**
   * Establece una `Session` como revocada. También se elimina la Cookie de sesión
   *
   * @param Request $req
   * @param Response $res
   */
  public static function DELETE(Request $req, Response $res) {
    try {
      // Recuperar sesión
      $session = SessionsModel::getActiveSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Eliminar sesión y cookie
      SessionsModel::delete($session["id"]);

      $res->deleteCookie("session_token");
      $res->setData(["session_deleted" => true]);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Crea un `Session`. Si el cuerpo de la petición no contiene los parámetros
   * requeridos con el formato correcto, se mostrará un error
   *
   * Si se crea correctamente se creará la Cookie de sesión
   *
   * @param Request $req
   * @param Response $res
   */
  public static function POST(Request $req, Response $res) {
    try {
      // Obtener datos
      $data = $req->getData();

      $result = SessionSchema::validate($data);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      $user = UsersModel::getUserByUsername($data["username"], confidentialData: true);

      if ($user === null || !Encrypt::checkMasterPassword($result["data"]["master_password"], $user["master_password"])) {
        $res->addError("El usuario no existe o la contraseña es incorrecta");
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Comprobar 2FA (si está habilitado)
      if ($user[UsersModel::COL_TOTP_2FA_SECRET] !== null && $result["data"]["two_fa_code"] === null) {
        $res->setData([ "two_fa_enabled" => true ]);
        $res->showResponseAndExit(HttpCode::OK);
      }

      // Verificar 2FA (si está habilitado)
      if ($user[UsersModel::COL_TOTP_2FA_SECRET] !== null) {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $user[UsersModel::COL_TOTP_2FA_SECRET];
        $code = $ga->getCode($secret);

        if ($code !== $result["data"]["two_fa_code"]) {
          $res->addError("El código de la autenticación de doble factor es incorrecto");
          $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
        }
      }

      // Crear sesión
      $tokenExpiresAt = time() + $result["data"]["session_duration"]->value;
      $newSession = SessionsModel::create(
        $user["id"],
        $req->getUserAgent(),
        $tokenExpiresAt
      );

      // Crear Cookie de sesión
      $res->setCookie(
        name: "session_token",
        value: $newSession["token"],
        expires: $tokenExpiresAt
      );

      unset($newSession[SessionsModel::COL_TOKEN]);
      $res->setData($newSession);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
